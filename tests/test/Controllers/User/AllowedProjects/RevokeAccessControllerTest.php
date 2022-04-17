<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class RevokeAccessControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $grantAccess = $container->make("dhope0000\LXDClient\Tools\User\AllowedProjects\GrantAccess");
        $grantAccess->grant(1, 2, [1], ["default"]);
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_nonAdminTryingToRevokeAccess() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/User/AllowedProjects/RevokeAccessController/revoke",
            "POST",
            ["targetUser"=>1, "hostId"=>1, "project"=>"default"],
            [],
            [],
            ["HTTP_USERID"=>2],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_adminTryingToRevokeAdminAccess() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/User/AllowedProjects/RevokeAccessController/revoke",
            "POST",
            ["targetUser"=>1, "hostId"=>1, "project"=>"default"],
            [],
            [],
            ["HTTP_USERID"=>1],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_adminRevokingUserAccess() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/User/AllowedProjects/RevokeAccessController/revoke",
            "POST",
            ["targetUser"=>2, "hostId"=>1, "project"=>"default"],
            [],
            [],
            ["HTTP_USERID"=>1],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Revoked Access"], $result);
    }
}
