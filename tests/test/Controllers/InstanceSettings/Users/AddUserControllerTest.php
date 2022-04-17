<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class AddUserControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_nonAdminTryingToAddUser() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/Users/AddUserController/add",
            "POST",
            ["username"=>"cantAdd", "password"=>"test123"],
            [],
            [],
            ["HTTP_USERID"=>2, "HTTP_APITOKEN"=>"fakeToken", "HTTP_PROJECT"=>"testProject"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_adminCreatesUser() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/Users/AddUserController/add",
            "POST",
            ["username"=>"cantAdd", "password"=>"testlongpassword123"],
            [],
            [],
            ["HTTP_USERID"=>1, "HTTP_APITOKEN"=>"fakeToken", "HTTP_PROJECT"=>"testProject"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);

        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Addded user"], $result);
    }
}
