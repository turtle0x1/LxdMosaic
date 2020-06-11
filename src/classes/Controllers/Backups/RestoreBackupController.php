<?php

namespace dhope0000\LXDClient\Controllers\Backups;

use dhope0000\LXDClient\Tools\Backups\RestoreBackup;
use dhope0000\LXDClient\Objects\Host;

class RestoreBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $restoreBackup;

    public function __construct(RestoreBackup $restoreBackup)
    {
        $this->restoreBackup = $restoreBackup;
    }

    public function restore(int $backupId, Host $targetHost)
    {
        $response = $this->restoreBackup->restore($backupId, $targetHost);
        return ["state"=>"success", "message"=>"Restored Backup", "lxdResponse"=>$response];
    }
}
