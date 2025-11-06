<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class UpdateSettingsControllerTest extends TestCase
{
    private $routeApi;
    private $database;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $addHost = $container->make("dhope0000\LXDClient\Model\Hosts\AddHost");
        $addHost->addHost('localhostTwo', 'fake', 'fake', 'fake', 'duplicateAlias');
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->database->dbObject->rollBack();
    }

    public function testNonAdminCantUpdateHostsettings(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'hostId' => 1,
            'alias' => 'test',
            'supportsLoadAverages' => 0,
        ];

        $this->routeApi->route(
            Request::create('/api/Hosts/Settings/UpdateSettingsController/update', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testAdminCanUpdateHostSettings(): void
    {
        $_POST = [
            'hostId' => 1,
            'alias' => 'test',
            'supportsLoadAverages' => 0,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Hosts/Settings/UpdateSettingsController/update', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals([
            'state' => 'success',
            'messages' => 'Updated Settings',
        ], $result);
    }

    public function testDuplicateAliasThrowsException(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'hostId' => 1,
            'alias' => 'duplicateAlias',
            'supportsLoadAverages' => 0,
        ];

        $this->routeApi->route(
            Request::create('/api/Hosts/Settings/UpdateSettingsController/update', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );
    }
}
