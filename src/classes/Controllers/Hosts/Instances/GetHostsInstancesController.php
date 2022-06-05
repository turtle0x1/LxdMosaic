<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use dhope0000\LXDClient\Objects\Host;

class GetHostsInstancesController
{
    private $getHostsInstances;
    
    public function __construct(GetHostsInstances $getHostsInstances)
    {
        $this->getHostsInstances = $getHostsInstances;
    }
    public function get(Host $host)
    {
        return $this->getHostsInstances->getContainers($host);
    }
}
