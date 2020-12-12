<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SearchHostsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
        $users = $container->make("dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects");
        var_dump($users->fetchAll(2));
    }

    /**
     * I think this is useless its filtered out by orderParams isnt it
     */
    public function test_noAccessToSearchAnyHosts() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostSearch"=>"localhost"];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Hosts/SearchHosts/search')),
            ["userid"=>4],
            true
        );

        var_dump($result);
    }
}
