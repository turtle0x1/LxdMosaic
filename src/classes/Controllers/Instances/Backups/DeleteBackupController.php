<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Backups\DeleteRemoteBackup;
use Symfony\Component\Routing\Annotation\Route;

class DeleteBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteRemoteBackup $deleteRemoteBackup
    ) {
    }

    /**
     * @Route("/api/Instances/Backups/DeleteBackupController/delete", name="Delete Remote Instance Backup", methods={"POST"})
     */
    public function delete(Host $host, string $container, string $backup)
    {
        $lxdRespone = $this->deleteRemoteBackup->delete($host, $container, $backup);

        return [
            'state' => 'success',
            'message' => 'Deleted backup',
            'lxdRespone' => $lxdRespone,
        ];
    }
}
