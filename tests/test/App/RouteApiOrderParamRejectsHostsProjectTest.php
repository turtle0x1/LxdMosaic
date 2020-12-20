<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RouteApiOrderParamRejectsHostsProjectTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_orderParamRejectsOnOneHost() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>1];
        $this->routeApi->route(
            array_filter(explode('/', "/Hosts/GetHostOverviewController/get")),
            ["userid"=>2, "project"=>"default"]
        );
    }

    public function test_orderParamRejectsOnOneHostObject() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>1];
        $this->routeApi->route(
            array_filter(explode('/', "/Instances/MigrateInstanceController/migrate")),
            ["userid"=>2, "project"=>"default"]
        );
    }

    public function test_orderParamRejectsManyHosts() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hosts"=>[1]];
        $this->routeApi->route(
            array_filter(explode('/', "/CloudConfig/DeployController/deploy")),
            ["userid"=>2, "project"=>"default"]
        );
    }
}
