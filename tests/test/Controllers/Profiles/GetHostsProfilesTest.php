<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetHostsProfilesTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testGetHostsProjects(): void
    {
        $result = $this->routeApi->route(
            Request::create('/api/Profiles/GetAllProfilesController/getAllProfiles', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals(['clusters', 'standalone'], array_keys($result));

        $host = json_decode(json_encode($result['standalone']['members'][0]), true);
        $hostKeys = array_keys($host);

        $this->assertEquals([
            'hostId',
            'alias',
            'urlAndPort',
            'hostOnline',
            'supportsLoadAvgs',
            'currentProject',
            'profiles',
        ], $hostKeys);

        $this->assertEquals(['default', 'testProfile'], $host['profiles']);
    }
}
