<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class GetAllSettingsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function test_nonAdminCantGetAllSettings() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/GetAllSettingsController/getAll",
            "POST",
            [],
            [],
            [],
            ["HTTP_USERID"=>2, "HTTP_APITOKEN"=>"fakeToken"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_adminCanGetSettings() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/GetAllSettingsController/getAll",
            "POST",
            [],
            [],
            [],
            ["HTTP_USERID"=>1, "HTTP_APITOKEN"=>"fakeToken"],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $result = $this->routeApi->route(
            $request,
            $context,
            true
        );
        $this->assertTrue(count($result) > 0);
    }
}
