<?php

namespace dhope0000\LXDClient\Controllers\Containers\Backups;

use dhope0000\LXDClient\Tools\Containers\Backups\StoreBackupLocally;

class ImportBackupController
{
    public function __construct(StoreBackupLocally $storeBackupLocally)
    {
        $this->storeBackupLocally = $storeBackupLocally;
    }

    public function import(int $hostId, string $container, string $backup)
    {
        $this->storeBackupLocally->store($hostId, $container, $backup);

        return ["state"=>"success", "message"=>"Imported backup"];
    }
}
