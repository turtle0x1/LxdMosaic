<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\DeleteBackupSchedules;
use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\DeleteRecordedActions;
use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;
use dhope0000\LXDClient\Model\Users\ApiTokens\DeleteUserApiTokens;
use dhope0000\LXDClient\Model\Users\UpdateLoginStatus;
use dhope0000\LXDClient\Model\Users\UpdateUserDeletedStatus;
use dhope0000\LXDClient\Model\Users\UpdateUsername;

class DeleteUser
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly UpdateUserDeletedStatus $updateUserDeletedStatus,
        private readonly UpdateUsername $updateUsername,
        private readonly DeleteUserAccess $deleteUserAccess,
        private readonly DeleteUserApiTokens $deleteUserApiTokens,
        private readonly DeleteBackupSchedules $deleteBackupSchedules,
        private readonly DeleteRecordedActions $deleteRecordedActions,
        private readonly UpdateLoginStatus $updateLoginStatus
    ) {
    }

    public function delete(
        int $userId,
        int $targetUserId,
        int $changeUserName = 1,
        int $removeProjectAccess = 1,
        int $removeApiKeys = 1,
        int $deleteAuditLogs = 0,
        int $deleteBackupSchedules = 0
    ) {
        $this->validatePermissions->isAdminOrThrow($userId);

        $this->updateUserDeletedStatus->setDeleted($userId, $targetUserId);
        $this->updateLoginStatus->update($targetUserId, 1);
        $this->deleteUserApiTokens->deleteAllNonPermanent($targetUserId);

        if ($changeUserName) {
            $this->updateUsername->update($targetUserId, 'Deleted User');
        }

        if ($removeProjectAccess) {
            $this->deleteUserAccess->deleteForUser($targetUserId);
        }

        if ($removeApiKeys) {
            $this->deleteUserApiTokens->deleteAllPermanent($targetUserId);
        }

        if ($deleteAuditLogs) {
            $this->deleteRecordedActions->deleteForUser($targetUserId);
        }

        if ($deleteBackupSchedules) {
            $this->deleteBackupSchedules->deleteforUser($targetUserId);
        }

        return true;
    }
}
