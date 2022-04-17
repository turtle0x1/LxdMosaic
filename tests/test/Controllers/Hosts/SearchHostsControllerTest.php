<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

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
        $request =  new Request();
        $request = $request->create(
            "api/Hosts/SearchHosts/search",
            "POST",
            ["hostSearch"=>"localhost"],
            [],
            [],
            ["HTTP_USERID"=>2],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );
        $this->assertTrue(count($result) === 1);
    }

    public function test_accessToSearchAllHosts() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/Hosts/SearchHosts/search",
            "POST",
            ["hostSearch"=>"localhost"],
            [],
            [],
            ["HTTP_USERID"=>1],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );

        $this->assertTrue(count($result) === 2);
    }
}
