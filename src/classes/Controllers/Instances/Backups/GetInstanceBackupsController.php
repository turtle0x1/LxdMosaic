<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\GetInstanceBackups;
use dhope0000\LXDClient\Objects\Host;

class GetInstanceBackupsController
{
    private $getContainerBackups;

    public function __construct(GetInstanceBackups $getInstanceBackups)
    {
        $this->getInstanceBackups = $getInstanceBackups;
    }

    public function get(Host $host, string $container)
    {
        return $this->getInstanceBackups->get($host, $container);
    }
}
