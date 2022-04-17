<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class GetLastControllerTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $insertRecordedAction = $container->make("dhope0000\LXDClient\Model\InstanceSettings\RecordActions\InsertActionLog");
        $insertRecordedAction->insert(1, "dhope0000\LXDClient\Controllers\Containers\StateController", json_encode(["test"=>"test"]));
    }

    public function tearDown() :void
    {
        $this->database->dbObject->rollBack();
    }

    public function test_nonAdminCantGetAllSettings() :void
    {
        $this->expectException(\Exception::class);
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/RecordedActions/GetLastController/get",
            "POST",
            ["ammount"=>5],
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

    public function test_adminCanGetSettings() :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/InstanceSettings/RecordedActions/GetLastController/get",
            "POST",
            ["ammount"=>5],
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
        $this->assertTrue(count($result) > 0);
    }
}
