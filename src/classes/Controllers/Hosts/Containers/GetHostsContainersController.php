<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Containers;

use dhope0000\LXDClient\Tools\Containers\GetHostsContainers;

class GetHostsContainersController
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
