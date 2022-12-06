<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\Users\UpdateUserDeletedStatus;
use dhope0000\LXDClient\Model\Users\UpdateUsername;
use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;
use dhope0000\LXDClient\Model\Users\ApiTokens\DeleteUserApiTokens;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\DeleteBackupSchedules;
use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\DeleteRecordedActions;
use dhope0000\LXDClient\Model\Users\UpdateLoginStatus;

class DeleteUser
{
    private ValidatePermissions $validatePermissions;
    private UpdateUserDeletedStatus $updateUserDeletedStatus;
    private UpdateUsername $updateUsername;
    private DeleteUserAccess $deleteUserAccess;
    private DeleteUserApiTokens $deleteUserApiTokens;
    private DeleteBackupSchedules $deleteBackupSchedules;
    private DeleteRecordedActions $deleteRecordedActions;
    private UpdateLoginStatus $updateLoginStatus;

    public function __construct(
        ValidatePermissions $validatePermissions,
        UpdateUserDeletedStatus $updateUserDeletedStatus,
        UpdateUsername $updateUsername,
        DeleteUserAccess $deleteUserAccess,
        DeleteUserApiTokens $deleteUserApiTokens,
        DeleteBackupSchedules $deleteBackupSchedules,
        DeleteRecordedActions $deleteRecordedActions,
        UpdateLoginStatus $updateLoginStatus
    ) {
        $this->validatePermissions = $validatePermissions;
        $this->updateUserDeletedStatus = $updateUserDeletedStatus;
        $this->updateUsername = $updateUsername;
        $this->deleteUserAccess = $deleteUserAccess;
        $this->deleteUserApiTokens = $deleteUserApiTokens;
        $this->deleteBackupSchedules = $deleteBackupSchedules;
        $this->deleteRecordedActions = $deleteRecordedActions;
        $this->updateLoginStatus = $updateLoginStatus;
    }

    public function delete(
        int $userId,
        int $targetUserId,
        int $changeUserName = 1,
        int $removeProjectAccess = 1,
        int $removeApiKeys = 1,
        int $deleteAuditLogs = 0,
        int $deleteBackupSchedules = 0
    ) :bool {
        $this->validatePermissions->isAdminOrThrow($userId);

        $this->updateUserDeletedStatus->setDeleted($userId, $targetUserId);
        $this->updateLoginStatus->update($targetUserId, 1);
        $this->deleteUserApiTokens->deleteAllNonPermanent($targetUserId);

        if ($changeUserName) {
            $this->updateUsername->update($targetUserId, "Deleted User");
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
