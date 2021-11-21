<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetDashboardTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_dashboard_key_are_the_same() :void
    {
        $result = $this->routeApi->route(
            array_filter(explode('/', '/Dashboard/GetController/get')),
            ["userid"=>1],
            true
        );
        // Assert the top level of the response hasnt changed
        $exepctedTopKeys = ["userDashboards", "projectsUsageGraphData"];

        $this->assertEquals($exepctedTopKeys, array_keys($result));
    }
}
