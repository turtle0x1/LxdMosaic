<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class UpdateSettingsControllerTest extends TestCase
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
        $addHost->addHost("localhostTwo", "fake", "fake", "fake", "duplicateAlias");
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_nonAdminCantUpdateHostsettings() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/Hosts/Settings/UpdateSettingsController/update",
            "POST",
            ["hostId"=>1, "alias"=>"test", "supportsLoadAverages"=>0],
            [],
            [],
            ["HTTP_USERID"=>2],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $this->routeApi->route(
            $request,
            $context,
            true
        );
    }

    public function test_adminCanUpdateHostSettings() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/Hosts/Settings/UpdateSettingsController/update",
            "POST",
            ["hostId"=>1, "alias"=>"test", "supportsLoadAverages"=>0],
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

        $this->assertEquals(["state"=>"success", "messages"=>"Updated Settings"], $result);
    }


    public function test_duplicateAliasThrowsException() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/Hosts/Settings/UpdateSettingsController/update",
            "POST",
            ["hostId"=>1, "alias"=>"duplicateAlias", "supportsLoadAverages"=>0],
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
    }
}
