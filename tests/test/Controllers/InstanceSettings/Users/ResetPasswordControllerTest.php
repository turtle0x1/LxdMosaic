<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class ResetPasswordControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");

        $this->database = $container->get("dhope0000\LXDClient\Model\Database\Database");
        $this->database->dbObject->beginTransaction();

        $insertUser = $container->make("dhope0000\LXDClient\Model\Users\InsertUser");
        $insertUser->insert('fromLdapuser', 'hash', '123');
        $this->ldapUserId = $insertUser->getId();
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->database->dbObject->rollBack();
    }

    public function testNonAdminTryingToResetPassword(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'targetUser' => 2,
            'newPassword' => 'testlongpassword123',
        ];

        $this->routeApi->route(
            Request::create('/api/InstanceSettings/Users/ResetPasswordController/reset', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testTryingToResetLdapUserFails(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'targetUser' => $this->ldapUserId,
            'newPassword' => 'testlongpassword123',
        ];

        $this->routeApi->route(
            Request::create('/api/InstanceSettings/Users/ResetPasswordController/reset', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );
    }

    public function testAdminResetsPassword(): void
    {
        $_POST = [
            'targetUser' => 2,
            'newPassword' => 'testlongpassword123',
        ];

        $result = $this->routeApi->route(
            Request::create('/api/InstanceSettings/Users/ResetPasswordController/reset', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals([
            'state' => 'success',
            'message' => 'Updated password',
        ], $result);
    }
}
