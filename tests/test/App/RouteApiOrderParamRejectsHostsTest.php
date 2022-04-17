<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

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
        $request =  new Request();
        $request = $request->create(
            "api/Profiles/CopyProfileController/copyProfile",
            "POST",
            ["hostId"=>2],
            [],
            [],
            ["HTTP_USERID"=>3, "HTTP_APITOKEN"=>"FAKE", "HTTP_PROJECT"=>"default"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_orderParamRejectsOnOneHost() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/Profiles/CopyProfileController/copyProfile",
            "POST",
            ["hostId"=>2],
            [],
            [],
            ["HTTP_USERID"=>2, "HTTP_APITOKEN"=>"FAKE", "HTTP_PROJECT"=>"default"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_orderParamRejectsOnOneHostObject() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/Instances/MigrateInstanceController/migrate",
            "POST",
            ["hostId"=>2],
            [],
            [],
            ["HTTP_USERID"=>2, "HTTP_APITOKEN"=>"FAKE", "HTTP_PROJECT"=>"default"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_orderParamRejectsManyHosts() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/CloudConfig/DeployController/deploy",
            "POST",
            ["hosts"=>[2]],
            [],
            [],
            ["HTTP_USERID"=>2, "HTTP_APITOKEN"=>"FAKE", "HTTP_PROJECT"=>"default"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }
}
