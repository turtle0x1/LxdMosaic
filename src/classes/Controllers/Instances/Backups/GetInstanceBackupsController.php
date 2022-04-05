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
     * @Route("/api/Instances/Backups/GetInstanceBackupsController/get", methods={"POST"}, name="Get all backups for an instance", options={"rbac" = "lxdmosaic.instaces.backups.read"})
     */
    public function get(Host $host, string $container)
    {
        return $this->getInstanceBackups->get($host, $container);
    }
}
