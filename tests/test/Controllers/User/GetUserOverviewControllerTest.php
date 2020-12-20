<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetUserOverviewControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_non_admin_cant_access_user_recorded_actions() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["targetUser"=>1];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/User/GetUserOverviewController/get')),
            ["userid"=>2],
            true
        );
    }
}
