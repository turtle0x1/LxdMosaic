<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\BackupInstance;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class BackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $backupInstance;

    public function __construct(BackupInstance $backupInstance)
    {
        $this->backupInstance = $backupInstance;
    }
    /**
     * @Route("", name="Backup Instance")
     */
    public function backup(
        Host $host,
        string $container,
        string $backup,
        $wait = true,
        int $importAndDelete
    ) {
        $lxdRespone = $this->backupInstance->create(
            $host,
            $container,
            $host->callClientMethod("getProject"),
            $backup,
            $wait,
            (bool) $importAndDelete
        );

        $status = $wait === "false" ? "Backing" : "Backed";

        return ["state"=>"success", "message"=>"$status up container", "lxdRespone"=>$lxdRespone];
    }
}
