<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

final class GetAllSettingsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminCantGetAllSettings() :void
    {
        $this->expectException(\Exception::class);

        $result = $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/GetAllSettingsController/getAll')),
            ["userid"=>2],
            true
        );
    }

    public function test_adminCanGetSettings() :void
    {
        $result = $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/GetAllSettingsController/getAll')),
            ["userid"=>1],
            true
        );
        $this->assertTrue(count($result) > 0);
    }
}
