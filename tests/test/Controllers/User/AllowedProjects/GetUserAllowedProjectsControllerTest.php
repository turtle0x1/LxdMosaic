<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

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

        $_POST = ["targetUser"=>1];

        $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/GetUserAllowedProjectsController/get', 'POST'),
            ["userid"=>2],
            true
        );
    }

    public function test_adminGettingAdminAllowedProjects() :void
    {
        $_POST = ["targetUser"=>1];

        $result = $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/GetUserAllowedProjectsController/get', 'POST'),
            ["userid"=>1],
            true
        );

        $this->assertEquals(["isAdmin"=>true, "projects"=>[]], $result);
    }

    public function test_adminGettingNonAdminAllowedProjects() :void
    {
        $_POST = ["targetUser"=>2];

        $result = $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/GetUserAllowedProjectsController/get', 'POST'),
            ["userid"=>1],
            true
        );

        $this->assertTrue($result["isAdmin"] == false);
        $this->assertTrue(count($result["projects"]) > 0);
        $this->assertEquals($result["projects"][0]->getCustomProp("projects"), [
            "testProject"
        ]);
    }
}
