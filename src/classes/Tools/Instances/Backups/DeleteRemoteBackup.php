<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class DeleteRemoteBackup
{
    public function __construct(
        private readonly HasExtension $hasExtension
    ) {
    }

    public function delete(Host $host, string $instance, string $backup)
    {
        if ($this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP) !== true) {
            throw new \Exception("Host doesn't support backups", 1);
        }

        return $host->instances->backups->remove($instance, $backup, [], true);
    }
}
