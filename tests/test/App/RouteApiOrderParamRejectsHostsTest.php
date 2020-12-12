<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RouteApiOrderParamRejectsHostsTest extends TestCase
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
        $addHost->addHost("fake", "fake", "fake", "fake", "fake");
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_noAcessToAnyHosts() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>2];
        $this->routeApi->route(
            array_filter(explode("/", "/Profiles/CopyProfileController/copyProfile")),
            ["userid"=>3, "apitoken"=>"wow"]
        );
    }

    public function test_orderParamRejectsOnOneHost() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>2];
        $this->routeApi->route(
            array_filter(explode("/", "/Profiles/CopyProfileController/copyProfile")),
            ["userid"=>2, "project"=>"default"]
        );
    }

    public function test_orderParamRejectsOnOneHostObject() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>2];
        $this->routeApi->route(
            array_filter(explode("/", "/Instances/MigrateInstanceController/migrate")),
            ["userid"=>2, "project"=>"default"]
        );
    }

    public function test_orderParamRejectsManyHosts() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hosts"=>[2]];
        $this->routeApi->route(
            array_filter(explode("/", "/CloudConfig/DeployController/deploy")),
            ["userid"=>2, "project"=>"default"]
        );
    }
}
