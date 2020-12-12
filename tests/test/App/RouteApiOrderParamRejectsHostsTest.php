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

    public function test_orderParamRejectsOnOneHost() :void
    {
        $this->expectException(\Exception::class);
        $this->routeApi->orderParams(
            ["hostId"=>2],
            "dhope0000\LXDClient\Controllers\Profiles\CopyProfileController",
            "copyProfile",
            2,
            ["userId"=>2]
        );
    }

    public function test_orderParamRejectsOnOneHostObject() :void
    {
        $this->expectException(\Exception::class);
        $this->routeApi->orderParams(
            ["hostId"=>2],
            "dhope0000\LXDClient\Controllers\Instances\MigrateInstanceController",
            "migrate",
            2,
            ["userId"=>2]
        );
    }

    public function test_orderParamRejectsManyHosts() :void
    {
        $this->expectException(\Exception::class);
        $this->routeApi->orderParams(
            ["hosts"=>[2]],
            "dhope0000\LXDClient\Controllers\CloudConfig\DeployController",
            "deploy",
            2,
            ["userId"=>2]
        );
    }
}
