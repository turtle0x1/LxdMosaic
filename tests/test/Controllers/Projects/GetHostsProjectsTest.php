<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class GetHostsProjectsTest extends TestCase
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
        $request =  new Request();
        $request = $request->create(
            "api/Projects/GetHostsProjectsController/get",
            "POST",
            [],
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
            'projects'
        ], $hostKeys);

        $this->assertEquals([
            "default",
            "testProject"
        ], $host["projects"]);
    }
}
