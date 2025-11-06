<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\DeleteLocalBackup;
use Symfony\Component\Routing\Attribute\Route;

class DeleteLocalBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteLocalBackup $deleteLocalBackup
    ) {
    }

    #[Route(path: '/api/Instances/Backups/DeleteLocalBackupController/delete', name: 'Delete Local Instance Backup', methods: ['POST'])]
    public function delete(int $userId, int $backupId)
    {
        $lxdRespone = $this->deleteLocalBackup->delete($userId, $backupId);

        return [
            'state' => 'success',
            'message' => 'Deleted backup',
            'lxdRespone' => $lxdRespone,
        ];
    }
}
