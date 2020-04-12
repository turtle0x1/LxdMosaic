<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\StoreBackupLocally;

class ImportBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(StoreBackupLocally $storeBackupLocally)
    {
        $this->storeBackupLocally = $storeBackupLocally;
    }

    public function import(int $hostId, string $container, string $backup, int $delete)
    {
        $this->storeBackupLocally->store($hostId, $container, $backup, (bool) $delete);

        return ["state"=>"success", "message"=>"Imported backup"];
    }
}
