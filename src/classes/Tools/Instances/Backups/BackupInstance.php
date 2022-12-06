<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\StoreBackupLocally;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Objects\Host;

class BackupInstance
{
    private StoreBackupLocally $storeBackupLocally;

    private HasExtension $hasExtension;

    public function __construct(
        StoreBackupLocally $storeBackupLocally,
        HasExtension $hasExtension
    ) {
        $this->storeBackupLocally = $storeBackupLocally;
        $this->hasExtension = $hasExtension;
    }

    public function create(
        Host $host,
        string $instance,
        string $project,
        string $backup,
        $wait,
        bool $importAndDelete
    ) {
        if ($wait === "false") {
            $wait = false;
        }

        if ($importAndDelete == true) {
            $wait = true;
        }

        if ($this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP) !== true) {
            throw new \Exception("Host doesn't support backups", 1);
        }

        $host->callClientMethod("setProject", $project);
        $response = $host->instances->backups->create($instance, $backup, [], $wait);

        if ($importAndDelete) {
            $this->storeBackupLocally->store($host, $project, $instance, $backup, true);
        }

        return $response;
    }
}
