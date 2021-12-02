<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetHostOverviewControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_noAccesToGetHostOverview() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>2];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Hosts/GetHostOverviewController/get')),
            ["userid"=>2],
            true
        );
    }

    public function test_hasAccessToHost() :void
    {
        $_POST = ["hostId"=>1];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Hosts/GetHostOverviewController/get')),
            ["userid"=>1],
            true
        );

        $this->assertEquals([
            'header',
            'resources',
            'warnings',
            'projectAnalytics'
        ], array_keys($result));

        $this->assertInstanceOf('dhope0000\LXDClient\Objects\Host', $result["header"]);
    }
}
