<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetUserOverviewControllerTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNonAdminCantAccessUserRecordedActions(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'targetUser' => 1,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/User/GetUserOverviewController/get', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }
}
