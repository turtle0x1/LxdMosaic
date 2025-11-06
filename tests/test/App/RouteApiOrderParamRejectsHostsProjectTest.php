<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class RouteApiOrderParamRejectsHostsProjectTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testOrderParamRejectsOnOneHost(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'hostId' => 1,
        ];
        $this->routeApi->route(
            Request::create('/api/Hosts/GetHostOverviewController/get', 'POST'),
            [
                'userid' => 2,
                'project' => 'default',
            ]
        );
    }

    public function testOrderParamRejectsOnOneHostObject(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'hostId' => 1,
        ];
        $this->routeApi->route(
            Request::create('/api/Instances/MigrateInstanceController/migrate', 'POST'),
            [
                'userid' => 2,
                'project' => 'default',
            ]
        );
    }

    public function testOrderParamRejectsManyHosts(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'hosts' => [1],
        ];
        $this->routeApi->route(
            Request::create('/api/CloudConfig/DeployController/deploy', 'POST'),
            [
                'userid' => 2,
                'project' => 'default',
            ]
        );
    }
}
