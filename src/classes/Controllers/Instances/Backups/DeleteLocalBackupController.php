<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\DeleteLocalBackup;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteLocalBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deleteLocalBackup;

    public function __construct(DeleteLocalBackup $deleteLocalBackup)
    {
        $this->deleteLocalBackup = $deleteLocalBackup;
    }
    /**
     * @Route("/api/Instances/Backups/DeleteLocalBackupController/delete", methods={"POST"}, name="Delete Local Instance Backup", options={"rbac" = "lxdmosaic.backups.delete"})
     */
    public function delete(int $userId, int $backupId)
    {
        $lxdRespone = $this->deleteLocalBackup->delete($userId, $backupId);

        return ["state"=>"success", "message"=>"Deleted backup", "lxdRespone"=>$lxdRespone];
    }
}
