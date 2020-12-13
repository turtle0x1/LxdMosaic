<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

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

        $_POST = ["ammount"=>5];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/RecordedActions/GetLastController/get')),
            ["userid"=>2],
            true
        );
    }

    public function test_adminCanGetSettings() :void
    {
        $_POST = ["ammount"=>5];

        $result = $this->routeApi->route(
            array_filter(explode('/', '/InstanceSettings/RecordedActions/GetLastController/get')),
            ["userid"=>1],
            true
        );
        $this->assertTrue(count($result) > 0);
    }
}
