<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

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
            Request::create("/api/Hosts/GetHostOverviewController/get", 'POST'),
            ["userid"=>2, "project"=>"default"]
        );
    }

    public function test_orderParamRejectsOnOneHostObject() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>1];
        $this->routeApi->route(
            Request::create("/api/Instances/MigrateInstanceController/migrate", 'POST'),
            ["userid"=>2, "project"=>"default"]
        );
    }

    public function test_orderParamRejectsManyHosts() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hosts"=>[1]];
        $this->routeApi->route(
            Request::create("/api/CloudConfig/DeployController/deploy", 'POST'),
            ["userid"=>2, "project"=>"default"]
        );
    }
}
