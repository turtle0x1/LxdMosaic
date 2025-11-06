<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GrantAccessControllerTest extends TestCase
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
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->database->dbObject->rollBack();
    }

    public function testNonAdminUserTryingToGrantAccess(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'targetUser' => 1,
            'hosts' => [1],
            'projects' => ['default'],
        ];

        $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/GrantAccessController/grant', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testGrantUserAccess(): void
    {
        $_POST = [
            'targetUser' => 2,
            'hosts' => [1],
            'projects' => ['default'],
        ];

        $result = $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/GrantAccessController/grant', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals([
            'state' => 'success',
            'message' => 'Granted Access',
        ], $result);
    }
}
