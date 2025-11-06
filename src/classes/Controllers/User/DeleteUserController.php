<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\DeleteUser;
use DI\Container;
use Symfony\Component\Routing\Attribute\Route;

class DeleteUserController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteUser $deleteUser,
        private readonly Container $container
    ) {
    }

    #[Route(path: '/api/User/DeleteUserController/delete', name: 'Delete a user', methods: ['POST'])]
    public function delete(
        int $userId,
        int $targetUserId,
        int $changeUserName = 1,
        int $removeProjectAccess = 1,
        int $removeApiKeys = 1,
        int $deleteAuditLogs = 0,
        int $deleteBackupSchedules = 0
    ) {
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'beginTransaction']);
        $this->deleteUser->delete(
            $userId,
            $targetUserId,
            $changeUserName,
            $removeProjectAccess,
            $removeApiKeys,
            $deleteAuditLogs,
            $deleteBackupSchedules
        );
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", 'commitTransaction']);
        return [
            'state' => 'success',
            'message' => 'Deleted user',
        ];
    }
}
