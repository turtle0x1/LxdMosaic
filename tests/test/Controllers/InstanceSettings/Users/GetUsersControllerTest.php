<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetUsersControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminTryingToGetUsers() :void
    {
        $this->expectException(\Exception::class);

        $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/Users/GetUsersController/getAll')),
            ["userid"=>2],
            true
        );
    }

    public function test_adminGetsUsers() :void
    {
        $result = $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/Users/GetUsersController/getAll')),
            ["userid"=>1],
            true
        );

        $this->assertTrue(count($result) == 3);
    }
}
