<?php

namespace dhope0000\LXDClient\Controllers\Containers\Backups;

use dhope0000\LXDClient\Tools\Containers\Backups\DeleteRemoteBackup;

class DeleteBackupController
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
