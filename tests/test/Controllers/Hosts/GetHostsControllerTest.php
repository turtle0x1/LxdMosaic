<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetHostsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminTryingToGetAllHosts() :void
    {
        $this->expectException(\Exception::class);

        $this->routeApi->route(
            array_filter(explode('/', '/Hosts/GetHostsController/getAllHosts')),
            ["userid"=>2],
            true
        );
    }

    public function test_adminGettingAllHosts() :void
    {
        $result = $this->routeApi->route(
            array_filter(explode('/', '/Hosts/GetHostsController/getAllHosts')),
            ["userid"=>1],
            true
        );

        $this->assertTrue(count($result) === 1);
    }
}
