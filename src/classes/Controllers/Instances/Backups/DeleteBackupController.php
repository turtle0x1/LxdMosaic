<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\DeleteRemoteBackup;
use dhope0000\LXDClient\Objects\Host;

class DeleteBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deleteRemoteBackup;

    public function __construct(DeleteRemoteBackup $deleteRemoteBackup)
    {
        $this->deleteRemoteBackup = $deleteRemoteBackup;
    }

    public function delete(Host $host, string $container, string $backup)
    {
        $lxdRespone = $this->deleteRemoteBackup->delete($host, $container, $backup);

        return ["state"=>"success", "message"=>"Deleted backup", "lxdRespone"=>$lxdRespone];
    }
}
