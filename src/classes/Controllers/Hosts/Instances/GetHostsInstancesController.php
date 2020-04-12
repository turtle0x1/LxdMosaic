<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Instances\GetHostsContainers;

class GetHostsInstancesController
{
    public function __construct(GetHostsContainers $getHostsContainers)
    {
        $this->getHostsContainers = $getHostsContainers;
    }
    public function get(int $hostId)
    {
        return $this->getHostsContainers->getContainers($hostId);
    }
}
