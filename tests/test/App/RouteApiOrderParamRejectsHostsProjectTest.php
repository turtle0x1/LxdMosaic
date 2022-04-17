<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class RouteApiOrderParamRejectsHostsProjectTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_orderParamRejectsOnOneHost() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/Hosts/GetHostOverviewController/get",
            "POST",
            ["hostId"=>1],
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
            ["hostId"=>1],
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
            ["hosts"=>[1]],
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
