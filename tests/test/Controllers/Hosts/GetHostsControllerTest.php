<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetHostsControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNonAdminTryingToGetAllHosts(): void
    {
        $this->expectException(\Exception::class);

        $this->routeApi->route(
            Request::create('/api/Hosts/GetHostsController/getAllHosts', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testAdminGettingAllHosts(): void
    {
        $result = $this->routeApi->route(
            Request::create('/api/Hosts/GetHostsController/getAllHosts', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertTrue(count($result) === 1);
    }
}
