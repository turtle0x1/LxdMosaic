<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class GetUsersControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminTryingToGetUsers() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/Users/GetUsersController/getAll",
            "POST",
            ["username"=>"cantAdd", "password"=>"test123"],
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

    public function test_adminGetsUsers() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/Users/GetUsersController/getAll",
            "POST",
            [],
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

        $this->assertTrue(count($result) == 3);
    }
}
