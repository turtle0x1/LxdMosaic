<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

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

        $_POST = ["targetUser"=>1, "hostId"=>1, "project"=>"default"];

        $this->routeApi->route(
            array_filter(explode('/', '/User/AllowedProjects/RevokeAccessController/revoke')),
            ["userid"=>2],
            true
        );
    }

    public function test_adminTryingToRevokeAdminAccess() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["targetUser"=>1, "hostId"=>1, "project"=>"default"];

        $this->routeApi->route(
            array_filter(explode('/', '/User/AllowedProjects/RevokeAccessController/revoke')),
            ["userid"=>1],
            true
        );
    }

    public function test_adminRevokingUserAccess() :void
    {
        $_POST = ["targetUser"=>2, "hostId"=>1, "project"=>"default"];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/User/AllowedProjects/RevokeAccessController/revoke')),
            ["userid"=>1],
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Revoked Access"], $result);
    }
}
