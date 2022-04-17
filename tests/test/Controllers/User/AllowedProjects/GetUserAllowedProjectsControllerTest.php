<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class GetUserAllowedProjectsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminTryingToGetUserAllowedProjects() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/User/AllowedProjects/GetUserAllowedProjectsController/get",
            "POST",
            ["targetUser"=>1],
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

    public function test_adminGettingAdminAllowedProjects() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/User/AllowedProjects/GetUserAllowedProjectsController/get",
            "POST",
            ["targetUser"=>1],
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

        $this->assertEquals(["isAdmin"=>true, "projects"=>[]], $result);
    }

    public function test_adminGettingNonAdminAllowedProjects() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/User/AllowedProjects/GetUserAllowedProjectsController/get",
            "POST",
            ["targetUser"=>2],
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

        $this->assertTrue($result["isAdmin"] == false);
        $this->assertTrue(count($result["projects"]) > 0);
        $this->assertEquals($result["projects"][0]->getCustomProp("projects"), [
            "testProject"
        ]);
    }
}
