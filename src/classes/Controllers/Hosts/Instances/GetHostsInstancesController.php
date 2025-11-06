<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use Symfony\Component\Routing\Attribute\Route;

class GetHostsInstancesController
{
    public function __construct(
        private readonly GetHostsInstances $getHostsInstances
    ) {
    }

    #[Route(path: '/api/Hosts/Instances/GetHostsInstancesController/get', name: 'api_hosts_instances_gethostsinstancescontroller_get', methods: ['POST'])]
    public function get(Host $host)
    {
        return $this->getHostsInstances->getContainers($host);
    }
}
