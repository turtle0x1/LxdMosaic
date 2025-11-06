<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetUsersControllerTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNonAdminTryingToGetUsers(): void
    {
        $this->expectException(\Exception::class);

        $this->routeApi->route(
            Request::create('/api/InstanceSettings/Users/GetUsersController/getAll', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testAdminGetsUsers(): void
    {
        $result = $this->routeApi->route(
            Request::create('/api/InstanceSettings/Users/GetUsersController/getAll', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertTrue(count($result) == 3);
    }
}
