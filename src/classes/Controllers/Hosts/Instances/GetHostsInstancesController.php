<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsInstancesController
{
    private $getHostsInstances;
    
    public function __construct(GetHostsInstances $getHostsInstances)
    {
        $this->getHostsInstances = $getHostsInstances;
    }
    /**
     * @Route("/api/Hosts/Instances/GetHostsInstancesController/get", name="api_hosts_instances_gethostsinstancescontroller_get", methods={"POST"})
     */
    public function get(Host $host)
    {
        return $this->getHostsInstances->getContainers($host);
    }
}
