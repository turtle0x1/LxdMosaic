<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class SaveLdapSettingsControllerTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNonAdminCantSaveSettings(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'settings' => [],
        ];

        $this->routeApi->route(
            Request::create('/api/InstanceSettings/Ldap/SaveLdapSettingsController/save', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }
}
