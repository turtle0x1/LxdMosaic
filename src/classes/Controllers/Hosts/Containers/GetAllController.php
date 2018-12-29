<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Containers;

use dhope0000\LXDClient\Model\Containers\GetHostsContainers;

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
