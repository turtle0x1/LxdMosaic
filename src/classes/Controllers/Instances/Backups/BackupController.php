<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\BackupInstance;

class BackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $backupInstance;

    public function __construct(BackupInstance $backupInstance)
    {
        $this->backupInstance = $backupInstance;
    }

    public function backup(
        int $hostId,
        string $container,
        string $backup,
        $wait = true,
        int $importAndDelete
    ) {
        $lxdRespone = $this->backupInstance->create(
            $hostId,
            $container,
            $backup,
            $wait,
            (bool) $importAndDelete
        );

        $status = $wait === "false" ? "Backing" : "Backed";

        return ["state"=>"success", "message"=>"$status up container", "lxdRespone"=>$lxdRespone];
    }
}
