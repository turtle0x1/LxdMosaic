<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
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
            Request::create('/api/InstanceSettings/Ldap/SaveLdapSettingsController/save', 'POST'),
            ["userid"=>2],
            true
        );
    }
}
