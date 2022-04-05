<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Hosts\Instances\GetAllProxyDevices;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetAllProxyDevicesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(GetAllProxyDevices $getAllProxyDevices)
    {
        $this->getAllProxyDevices = $getAllProxyDevices;
    }
    /**
     * @Route("/api/Hosts/Instances/GetAllProxyDevicesController/get", methods={"POST"}, name="Get all proxy devices", options={"rbac" = "hosts.proxies.read"})
     */
    public function get(Host $host)
    {
        return $this->getAllProxyDevices->get($host);
    }
}
