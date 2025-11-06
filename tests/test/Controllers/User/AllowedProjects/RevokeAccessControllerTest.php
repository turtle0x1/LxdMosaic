<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class RevokeAccessControllerTest extends TestCase
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

        $grantAccess = $container->make("dhope0000\LXDClient\Tools\User\AllowedProjects\GrantAccess");
        $grantAccess->grant(1, 2, [1], ['default']);
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->database->dbObject->rollBack();
    }

    public function testNonAdminTryingToRevokeAccess(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'targetUser' => 1,
            'hostId' => 1,
            'project' => 'default',
        ];

        $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/RevokeAccessController/revoke', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testAdminTryingToRevokeAdminAccess(): void
    {
        $this->expectException(\Exception::class);
        $_POST = [
            'targetUser' => 1,
            'hostId' => 1,
            'project' => 'default',
        ];

        $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/RevokeAccessController/revoke', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );
    }

    public function testAdminRevokingUserAccess(): void
    {
        $_POST = [
            'targetUser' => 2,
            'hostId' => 1,
            'project' => 'default',
        ];

        $result = $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/RevokeAccessController/revoke', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals([
            'state' => 'success',
            'message' => 'Revoked Access',
        ], $result);
    }
}
