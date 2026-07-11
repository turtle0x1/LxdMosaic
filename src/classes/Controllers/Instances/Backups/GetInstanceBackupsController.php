<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Backups\GetInstanceBackups;
use Symfony\Component\Routing\Attribute\Route;

class GetInstanceBackupsController
{
    public function __construct(
        private readonly GetInstanceBackups $getInstanceBackups
    ) {
    }

    #[Route(path: '/api/Instances/Backups/GetInstanceBackupsController/get', name: 'Get instance backups', methods: ['POST'])]
    public function get(Host $host, string $container)
    {
        return $this->getInstanceBackups->get($host, $container);
    }
}
