<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GrantAccessControllerTest extends TestCase
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

    public function test_nonAdminUserTryingToGrantAccess() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["targetUser"=>1, "hosts"=>[1], "projects"=>["default"]];

        $this->routeApi->route(
            array_filter(explode('/', '/User/AllowedProjects/GrantAccessController/grant')),
            ["userid"=>2],
            true
        );
    }

    public function test_grantUserAccess() :void
    {
        $_POST = ["targetUser"=>2, "hosts"=>[1], "projects"=>["default"]];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/User/AllowedProjects/GrantAccessController/grant')),
            ["userid"=>1],
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Granted Access"], $result);
    }
}
