<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetClusterControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNonAdminCantAccessClusterOverview(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'cluster' => 1,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Clusters/GetClusterController/get', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }
}
