<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class FirstRunControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testFirstRunCantRunIfAdminPassSet(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'adminPassword' => 'test123',
            'hosts' => [],
        ];

        $result = $this->routeApi->route(
            Request::create('/api/InstanceSettings/FirstRunController/run', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }
}
