<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetLastControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $insertRecordedAction = $container->make(
            "dhope0000\LXDClient\Model\InstanceSettings\RecordActions\InsertActionLog"
        );
        $insertRecordedAction->insert(1, "dhope0000\LXDClient\Controllers\Containers\StateController", json_encode([
            'test' => 'test',
        ]));
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->database->dbObject->rollBack();
    }

    public function testNonAdminCantGetAllSettings(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'ammount' => 5,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/InstanceSettings/RecordedActions/GetLastController/get', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testAdminCanGetSettings(): void
    {
        $_POST = [
            'ammount' => 5,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/InstanceSettings/RecordedActions/GetLastController/get', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );
        $this->assertTrue(count($result) > 0);
    }
}
