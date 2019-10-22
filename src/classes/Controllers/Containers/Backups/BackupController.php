<?php

namespace dhope0000\LXDClient\Controllers\Containers\Backups;

use dhope0000\LXDClient\Tools\Containers\Backups\BackupContainer;

class BackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $backupContainer;

    public function __construct(BackupContainer $backupContainer)
    {
        $this->backupContainer = $backupContainer;
    }

    public function backup(
        int $hostId,
        string $container,
        string $backup,
        $wait = true,
        int $importAndDelete
    ) {
        $lxdRespone = $this->backupContainer->create(
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
