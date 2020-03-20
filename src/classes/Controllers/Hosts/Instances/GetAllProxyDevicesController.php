<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Hosts\Instances\GetAllProxyDevices;

class GetAllProxyDevicesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(GetAllProxyDevices $getAllProxyDevices)
    {
        $this->getAllProxyDevices = $getAllProxyDevices;
    }

    public function get(int $hostId)
    {
        return $this->getAllProxyDevices->get($hostId);
    }
}
