<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetMySettingsOverviewControllerTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testUserCanGetTheirSettings(): void
    {
        $result = $this->routeApi->route(
            Request::create('/api/InstanceSettings/GetMySettingsOverviewController/get', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );

        $this->assertEquals(['permanentTokens'], array_keys($result));
    }
}
