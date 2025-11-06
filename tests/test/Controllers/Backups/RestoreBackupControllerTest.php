<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class RestoreBackupControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();
        $inesrtBackup = $container->make("dhope0000\LXDClient\Model\Hosts\Backups\Instances\InsertInstanceBackup");
        $this->backupId = $inesrtBackup->insert(
            new \DateTime(),
            1,
            'default',
            'fakeInstance',
            'fakeBackupName',
            '/not/a/real/path',
            0
        );
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->database->dbObject->rollBack();
    }

    public function testNoAccesToProjectDoesntAllowRestore(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'backupId' => $this->backupId,
            'targetHost' => 1,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Backups/RestoreBackupController/restore', 'POST'),
            [
                'userid' => 2,
                'project' => 'testProject',
            ],
            true
        );
    }
}
