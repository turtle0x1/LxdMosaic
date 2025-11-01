<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\GetInstanceBackups;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetInstanceBackupsController
{
    private $getInstanceBackups;

    public function __construct(GetInstanceBackups $getInstanceBackups)
    {
        $this->getInstanceBackups = $getInstanceBackups;
    }

    /**
     * @Route("/api/Instances/Backups/GetInstanceBackupsController/get", name="api_instances_backups_getinstancebackupscontroller_get", methods={"POST"})
     */
    public function get(Host $host, string $container)
    {
        return $this->getInstanceBackups->get($host, $container);
    }
}
