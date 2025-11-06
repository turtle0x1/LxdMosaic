<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\Backups\DeleteBackup;
use dhope0000\LXDClient\Model\Backups\FetchBackup;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class DeleteLocalBackup
{
    public function __construct(
        private readonly FetchBackup $fetchBackup,
        private readonly DeleteBackup $deleteBackup,
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    public function delete(int $userId, int $backupId)
    {
        $backup = $this->fetchBackup->fetch($backupId);

        $this->validatePermissions->canAccessHostProjectOrThrow($userId, $backup['hostId'], $backup['project']);

        if (empty($backup)) {
            throw new \Exception('Cant find backup!', 1);
        }

        if (file_exists($backup['localPath'])) {
            unlink($backup['localPath']);
        }
        $this->deleteBackup->setDeleted($backupId);
        return true;
    }
}
