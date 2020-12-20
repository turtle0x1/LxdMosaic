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

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();
        $addHost = $container->make("dhope0000\LXDClient\Model\Hosts\AddHost");
        $addHost->addHost("localhostTwo", "fake", "fake", "fake", "localHostTwo");
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_accessToSearchLimitedHosts() :void
    {
        $_POST = ["hostSearch"=>"localhost"];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Hosts/SearchHosts/search')),
            ["userid"=>2],
            true
        );

        $this->assertTrue(count($result) === 1);
    }

    public function test_accessToSearchAllHosts() :void
    {
        $_POST = ["hostSearch"=>"localhost"];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Hosts/SearchHosts/search')),
            ["userid"=>1],
            true
        );

        $this->assertTrue(count($result) === 2);
    }
}
