<?php

namespace dhope0000\LXDClient\Controllers\Backups;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Backups\RestoreBackup;
use Symfony\Component\Routing\Attribute\Route;

class RestoreBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly RestoreBackup $restoreBackup
    ) {
    }

    #[Route(path: '/api/Backups/RestoreBackupController/restore', name: 'Restore Local Backup To Host', methods: ['POST'])]
    public function restore(int $userId, int $backupId, Host $targetHost)
    {
        $response = $this->restoreBackup->restore($userId, $backupId, $targetHost);
        return [
            'state' => 'success',
            'message' => 'Restored Backup',
            'lxdResponse' => $response,
        ];
    }
}
