<?php

namespace dhope0000\LXDClient\Controllers\Backups;

use dhope0000\LXDClient\Tools\Backups\RestoreBackup;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RestoreBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $restoreBackup;

    public function __construct(RestoreBackup $restoreBackup)
    {
        $this->restoreBackup = $restoreBackup;
    }
    /**
     * @Route("", name="Restore Local Backup To Host")
     */
    public function restore(int $userId, int $backupId, Host $targetHost)
    {
        $response = $this->restoreBackup->restore($userId, $backupId, $targetHost);
        return ["state"=>"success", "message"=>"Restored Backup", "lxdResponse"=>$response];
    }
}
