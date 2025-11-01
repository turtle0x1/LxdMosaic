<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

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

        $_POST = ["username"=>"cantAdd", "password"=>"test123"];

        $this->routeApi->route(
            Request::create('/api/InstanceSettings/Users/AddUserController/add', 'POST'),
            ["userid"=>2],
            true
        );
    }

    public function test_adminCreatesUser() :void
    {
        $_POST = ["username"=>"cantAdd", "password"=>"testlongpassword123"];

        $result = $this->routeApi->route(
            Request::create('/api/InstanceSettings/Users/AddUserController/add', 'POST'),
            ["userid"=>1],
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Addded user"], $result);
    }
}
