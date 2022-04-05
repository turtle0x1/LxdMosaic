<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Instances\GetHostsInstances;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsInstancesController
{
    public function __construct(GetHostsInstances $getHostsInstances)
    {
        $this->getHostsInstances = $getHostsInstances;
    }
    /**
     * @Route("/api/Hosts/Instances/GetHostsInstancesController/get", methods={"POST"}, name="Get all instances on a host", options={"rbac" = "hosts.instances.read"})
     */
    public function get(Host $host)
    {
        return $this->getHostsInstances->getContainers($host);
    }
}
