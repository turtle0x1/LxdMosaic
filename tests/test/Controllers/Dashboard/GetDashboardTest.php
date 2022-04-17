<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class GetDashboardTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_dashboard_key_are_the_same() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/Dashboard/GetController/get",
            "POST",
            ["cluster"=>1],
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
        
        // Assert the top level of the response hasnt changed
        $exepctedTopKeys = ["userDashboards", "projectsUsageGraphData"];

        $this->assertEquals($exepctedTopKeys, array_keys($result));
    }
}
