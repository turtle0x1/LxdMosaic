<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\GetInstanceBackups;

class GetInstanceBackupsController
{
    private $getContainerBackups;

    public function __construct(GetInstanceBackups $getInstanceBackups)
    {
        $this->getInstanceBackups = $getInstanceBackups;
    }

    public function get(int $hostId, string $container)
    {
        return $this->getInstanceBackups->get($hostId, $container);
    }
}
