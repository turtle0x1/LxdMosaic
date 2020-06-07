<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\StoreBackupLocally;
use dhope0000\LXDClient\Objects\Host;

class ImportBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(StoreBackupLocally $storeBackupLocally)
    {
        $this->storeBackupLocally = $storeBackupLocally;
    }

    public function import(Host $host, string $container, string $backup, int $delete)
    {
        $this->storeBackupLocally->store($host, $container, $backup, (bool) $delete);

        return ["state"=>"success", "message"=>"Imported backup"];
    }
}
