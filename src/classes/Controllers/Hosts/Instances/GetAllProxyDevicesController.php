<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\Instances\GetAllProxyDevices;
use Symfony\Component\Routing\Annotation\Route;

class GetAllProxyDevicesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetAllProxyDevices $getAllProxyDevices
    ) {
    }

    /**
     * @Route("/api/Hosts/Instances/GetAllProxyDevicesController/get", name="Get all proxy devices", methods={"POST"})
     */
    public function get(Host $host)
    {
        return $this->getAllProxyDevices->get($host);
    }
}
