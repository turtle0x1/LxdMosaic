<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;

class GetHostsInstancesController
{
    public function __construct(GetHostsInstances $getHostsInstances)
    {
        $this->getHostsInstances = $getHostsInstances;
    }
    public function get(int $hostId)
    {
        return $this->getHostsInstances->getContainers($hostId);
    }
}
