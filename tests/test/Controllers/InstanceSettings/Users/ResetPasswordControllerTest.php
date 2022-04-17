<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class ResetPasswordControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $insertUser = $container->make("dhope0000\LXDClient\Model\Users\InsertUser");
        $insertUser->insert("fromLdapuser", "hash", "123");
        $this->ldapUserId = $insertUser->getId();
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }


    public function test_nonAdminTryingToResetPassword() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/Users/ResetPasswordController/reset",
            "POST",
            ["targetUser"=>2, "newPassword"=>"testlongpassword123"],
            [],
            [],
            ["HTTP_USERID"=>2, "HTTP_APITOKEN"=>"fakeToken"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_tryingToResetLdapUserFails() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/Users/ResetPasswordController/reset",
            "POST",
            ["targetUser"=>$this->ldapUserId, "newPassword"=>"testlongpassword123"],
            [],
            [],
            ["HTTP_USERID"=>1, "HTTP_APITOKEN"=>"fakeToken"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_adminResetsPassword() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/Users/ResetPasswordController/reset",
            "POST",
            ["targetUser"=>2, "newPassword"=>"testlongpassword123"],
            [],
            [],
            ["HTTP_USERID"=>1, "HTTP_APITOKEN"=>"fakeToken"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Updated password"], $result);
    }
}
