<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\DeleteRemoteBackup;

class DeleteBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deleteRemoteBackup;

    public function __construct(DeleteRemoteBackup $deleteRemoteBackup)
    {
        $this->deleteRemoteBackup = $deleteRemoteBackup;
    }

    public function delete(int $hostId, string $container, string $backup)
    {
        $lxdRespone = $this->deleteRemoteBackup->delete($hostId, $container, $backup);

        return ["state"=>"success", "message"=>"Deleted backup", "lxdRespone"=>$lxdRespone];
    }
}
