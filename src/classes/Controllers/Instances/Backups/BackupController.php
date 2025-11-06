<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Backups\BackupInstance;
use Symfony\Component\Routing\Annotation\Route;

class BackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly BackupInstance $backupInstance
    ) {
    }

    /**
     * @Route("/api/Instances/Backups/BackupController/backup", name="Backup Instance", methods={"POST"})
     */
    public function backup(Host $host, string $container, string $backup, int $importAndDelete, $wait = true)
    {
        $lxdRespone = $this->backupInstance->create(
            $host,
            $container,
            $host->callClientMethod('getProject'),
            $backup,
            $wait,
            (bool) $importAndDelete
        );

        $status = $wait === 'false' ? 'Backing' : 'Backed';

        return [
            'state' => 'success',
            'message' => "{$status} up container",
            'lxdRespone' => $lxdRespone,
        ];
    }
}
