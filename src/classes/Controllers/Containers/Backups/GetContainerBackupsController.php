<?php

namespace dhope0000\LXDClient\Controllers\Containers\Backups;

use dhope0000\LXDClient\Tools\Containers\Backups\GetContainerBackups;

class GetContainerBackupsController
{
    private $getContainerBackups;

    public function __construct(GetContainerBackups $getContainerBackups)
    {
        $this->getContainerBackups = $getContainerBackups;
    }

    public function get(int $hostId, string $container)
    {
        return $this->getContainerBackups->get($hostId, $container);
    }
}
