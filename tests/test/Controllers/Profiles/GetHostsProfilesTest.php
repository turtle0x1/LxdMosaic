<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetHostsProfilesTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_getHostsProjects() :void
    {
        $result = $this->routeApi->route(
            array_filter(explode('/', '/Profiles/GetAllProfilesController/getAllProfiles')),
            ["userid"=>1],
            true
        );

        $this->assertEquals(["clusters", "standalone"], array_keys($result));

        $host = json_decode(json_encode($result["standalone"]["members"][0]), true);
        $hostKeys = array_keys($host);

        $this->assertEquals([
            'hostId',
            'alias',
            'urlAndPort',
            'hostOnline',
            'supportsLoadAvgs',
            'currentProject',
            'profiles'
        ], $hostKeys);

        $this->assertEquals([
            "default",
            "testProfile"
        ], $host["profiles"]);
    }
}
