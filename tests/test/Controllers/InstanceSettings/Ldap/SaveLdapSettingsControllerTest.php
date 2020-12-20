<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

final class SaveLdapSettingsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminCantSaveSettings() :void
    {
        $this->expectException(\Exception::class);

        $_POST = ["settings"=>[]];

        $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/Ldap/SaveLdapSettingsController/save')),
            ["userid"=>2],
            true
        );
    }
}
