<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DeleteHostControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminUserCantDeleteHost() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["hostId"=>1];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Hosts/DeleteHostController/delete')),
            ["userid"=>2],
            true
        );

        $this->assertEquals($expected, $result);
    }
}
