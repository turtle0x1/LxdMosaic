<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

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

        $_POST = ["targetUser"=>2, "newPassword"=>"testlongpassword123"];

        $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/Users/ResetPasswordController/reset')),
            ["userid"=>2],
            true
        );
    }

    public function test_tryingToResetLdapUserFails() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["targetUser"=>$this->ldapUserId, "newPassword"=>"testlongpassword123"];

        $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/Users/ResetPasswordController/reset')),
            ["userid"=>1],
            true
        );
    }

    public function test_adminResetsPassword() :void
    {
        $_POST = ["targetUser"=>2, "newPassword"=>"testlongpassword123"];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/Users/ResetPasswordController/reset')),
            ["userid"=>1],
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Updated password"], $result);
    }
}
