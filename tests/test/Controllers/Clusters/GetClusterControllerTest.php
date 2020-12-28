<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetClusterControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_non_admin_cant_access_cluster_overview() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["cluster"=>1];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Clusters/GetClusterController/get')),
            ["userid"=>2],
            true
        );
    }
}
