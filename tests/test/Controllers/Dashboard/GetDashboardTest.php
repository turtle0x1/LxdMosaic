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
        $exepctedTopKeys = ["userDashboards", "clustersAndHosts", "stats", "analyticsData"];

        $this->assertEquals($exepctedTopKeys, array_keys($result));
        // Assert hosts are being output with the expected keys from LXDMosaic
        // (if we wanted to check LXD we would need to check array keys for
        //  the resources array in the host)
        $hostKeys = array_keys(json_decode(json_encode($result["clustersAndHosts"]["standalone"]["members"][0]), true));

        $expectedKeys = [
            "hostId",
            "alias",
            "urlAndPort",
            "hostOnline",
            "supportsLoadAvgs",
            "projects",
            "currentProject",
            "resources"
        ];

        $this->assertEquals($expectedKeys, $hostKeys);
    }
}
