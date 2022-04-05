<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\DeleteRemoteBackup;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deleteRemoteBackup;

    public function __construct(DeleteRemoteBackup $deleteRemoteBackup)
    {
        $this->deleteRemoteBackup = $deleteRemoteBackup;
    }
    /**
     * @Route("/api/Instances/Backups/DeleteBackupController/delete", methods={"POST"}, name="Delete Remote Instance Backup", options={"rbac" = "instances.backups.delete"})
     */
    public function delete(Host $host, string $container, string $backup)
    {
        $lxdRespone = $this->deleteRemoteBackup->delete($host, $container, $backup);

        return ["state"=>"success", "message"=>"Deleted backup", "lxdRespone"=>$lxdRespone];
    }
}
