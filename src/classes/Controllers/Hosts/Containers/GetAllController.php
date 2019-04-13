<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Containers;

use dhope0000\LXDClient\Tools\Containers\GetHostsContainers;

class GetAllController
{
    public function __construct(GetHostsContainers $getHostsContainers)
    {
        $this->getHostsContainers = $getHostsContainers;
    }
    public function getAll()
    {
        return $this->getHostsContainers->getHostsContainers();
    }
}
