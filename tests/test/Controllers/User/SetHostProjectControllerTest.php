<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SetHostProjectControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $addHost = $container->make("dhope0000\LXDClient\Model\Hosts\AddHost");
        $addHost->addHost("localhostTwo", "fake", "fake", "fake", "localHostTwo");
        $this->newHostId = $addHost->getId();
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_userTryingToSetProjectOnAHostWithNoAccess() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["hostId"=>$this->newHostId, "project"=>"default"];

        $this->routeApi->route(
            array_filter(explode('/', '/User/SetHostProjectController/set')),
            ["userid"=>2],
            true
        );
    }

    public function test_userTryingToSetToProjectWithNoAcess() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["hostId"=>1, "project"=>"default"];

        $this->routeApi->route(
            array_filter(explode('/', '/User/SetHostProjectController/set')),
            ["userid"=>2],
            true
        );
    }

    public function test_userChangesProject() :void
    {
        $_POST = ["hostId"=>1, "project"=>"testProject"];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/User/SetHostProjectController/set')),
            ["userid"=>2],
            true
        );

        $this->assertEquals(["state"=>"success", "message"=>"Changed project to testProject"], $result);
    }
}
