<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\AddProxyDevice;

class AddProxyDeviceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(AddProxyDevice $addProxyDevice)
    {
        $this->addProxyDevice = $addProxyDevice;
    }

    public function add(Host $host, string $instance, string $name, string $source, string $destination)
    {
        $this->addProxyDevice->add($host, $instance, $name, $source, $destination);
        return ["state"=>"success", "message"=>"Added proxy device"];
    }
}
