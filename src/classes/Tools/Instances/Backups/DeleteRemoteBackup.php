<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Objects\Host;

class DeleteRemoteBackup
{
    private $hasExtension;

    public function __construct(HasExtension $hasExtension)
    {
        $this->hasExtension = $hasExtension;
    }

    public function delete(Host $host, string $instance, string $backup)
    {
        if ($this->hasExtension->hasWithHostId(
            $host->getHostId(),
            LxdApiExtensions::CONTAINER_BACKUP
        ) !== true) {
            throw new \Exception("Host doesn't support backups", 1);
        }

        return $host->instances->backups->remove($instance, $backup, [], true);
    }
}
