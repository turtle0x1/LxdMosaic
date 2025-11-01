<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\DeleteUser;
use Symfony\Component\Routing\Annotation\Route;
use \DI\Container;

class DeleteUserController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deleteUser;
    private $container;

    public function __construct(DeleteUser $deleteUser, Container $container)
    {
        $this->deleteUser = $deleteUser;
        $this->container = $container;
    }
    /**
     * @Route("/api/User/DeleteUserController/delete", name="Delete a user", methods={"POST"})
     */
    public function delete(
        int $userId,
        int $targetUserId,
        int $changeUserName = 1,
        int $removeProjectAccess = 1,
        int $removeApiKeys = 1,
        int $deleteAuditLogs = 0,
        int $deleteBackupSchedules = 0
    ) {
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "beginTransaction"]);
        $this->deleteUser->delete(
            $userId,
            $targetUserId,
            $changeUserName,
            $removeProjectAccess,
            $removeApiKeys,
            $deleteAuditLogs,
            $deleteBackupSchedules
        );
        $this->container->call(["dhope0000\LXDClient\Model\Database\Database", "commitTransaction"]);
        return ["state"=>"success", "message"=>"Deleted user"];
    }
}
