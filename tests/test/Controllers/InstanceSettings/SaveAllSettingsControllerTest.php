<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class SaveAllSettingsControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_nonAdminCantSaveSettings() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/SaveAllSettingsController/saveAll",
            "POST",
            ["settings"=>[]],
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

    public function test_adminCanSaveSettings() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/SaveAllSettingsController/saveAll",
            "POST",
            ["settings"=>[["id"=>InstanceSettingsKeys::STRONG_PASSWORD_POLICY, "value"=>0]]],
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

        $this->assertEquals(["state"=>"success", "message"=>"Saved Settings"], $result);
    }
}
