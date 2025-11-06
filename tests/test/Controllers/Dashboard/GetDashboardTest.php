<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetDashboardTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testDashboardKeyAreTheSame(): void
    {
        $result = $this->routeApi->route(
            Request::create('/api/Dashboard/GetController/get', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );
        // Assert the top level of the response hasnt changed
        $exepctedTopKeys = ['userDashboards', 'projectsUsageGraphData'];

        $this->assertEquals($exepctedTopKeys, array_keys($result));
    }
}
