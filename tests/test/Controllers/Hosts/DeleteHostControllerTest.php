<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class DeleteHostControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminUserCantDeleteHost() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/Hosts/DeleteHostController/delete",
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
}
