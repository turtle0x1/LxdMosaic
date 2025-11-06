<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetHostOverviewControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNoAccesToGetHostOverview(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'hostId' => 2,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Hosts/GetHostOverviewController/get', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testHasAccessToHost(): void
    {
        $_POST = [
            'hostId' => 1,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Hosts/GetHostOverviewController/get', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals(['header', 'resources', 'warnings', 'projectAnalytics'], array_keys($result));

        $this->assertInstanceOf('dhope0000\LXDClient\Objects\Host', $result['header']);
    }
}
