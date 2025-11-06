<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\AddProxyDevice;
use Symfony\Component\Routing\Attribute\Route;

class AddProxyDeviceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly AddProxyDevice $addProxyDevice
    ) {
    }

    #[Route(path: '/api/Hosts/Instances/AddProxyDeviceController/add', name: 'Add proxy device', methods: ['POST'])]
    public function add(Host $host, string $instance, string $name, string $source, string $destination)
    {
        $this->addProxyDevice->add($host, $instance, $name, $source, $destination);
        return [
            'state' => 'success',
            'message' => 'Added proxy device',
        ];
    }
}
