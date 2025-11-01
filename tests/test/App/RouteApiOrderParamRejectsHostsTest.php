<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class RouteApiOrderParamRejectsHostsTest extends TestCase
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
        $addHost->addHost("fake", "fake", "fake", "fake", "fake");
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_noAcessToAnyHosts() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>2];
        $this->routeApi->route(
            Request::create("/api/Profiles/CopyProfileController/copyProfile", 'POST'),
            ["userid"=>3, "apitoken"=>"wow"]
        );
    }

    public function test_orderParamRejectsOnOneHost() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>2];
        $this->routeApi->route(
            Request::create("/api/Profiles/CopyProfileController/copyProfile", 'POST'),
            ["userid"=>2, "project"=>"default"]
        );
    }

    public function test_orderParamRejectsOnOneHostObject() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hostId"=>2];
        $this->routeApi->route(
            Request::create("/api/Instances/MigrateInstanceController/migrate", 'POST'),
            ["userid"=>2, "project"=>"default"]
        );
    }

    public function test_orderParamRejectsManyHosts() :void
    {
        $this->expectException(\Exception::class);
        $_POST = ["hosts"=>[2]];
        $this->routeApi->route(
            Request::create("/api/CloudConfig/DeployController/deploy", 'POST'),
            ["userid"=>2, "project"=>"default"]
        );
    }
}
