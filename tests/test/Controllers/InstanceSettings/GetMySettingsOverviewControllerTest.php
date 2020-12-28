<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

final class GetMySettingsOverviewControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_userCanGetTheirSettings() :void
    {
        $result = $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/GetMySettingsOverviewController/get')),
            ["userid"=>2],
            true
        );

        $this->assertEquals(["permanentTokens"], array_keys($result));
    }
}
