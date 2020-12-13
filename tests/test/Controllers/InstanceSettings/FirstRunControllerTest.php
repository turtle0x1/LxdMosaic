<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

final class FirstRunControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_firstRunCantRunIfAdminPassSet() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["adminPassword"=>"test123", "hosts"=>[]];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/FirstRunController/run')),
            ["userid"=>2],
            true
        );
    }
}
