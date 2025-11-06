<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\DeleteProxyDevice;
use Symfony\Component\Routing\Attribute\Route;

class DeleteProxyDeviceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteProxyDevice $deleteProxyDevice
    ) {
    }

    #[Route(path: '/api/Hosts/Instances/DeleteProxyDeviceController/delete', name: 'Delete proxy device', methods: ['POST'])]
    public function delete(Host $host, string $instance, string $device)
    {
        $this->deleteProxyDevice->delete($host, $instance, $device);
        return [
            'state' => 'success',
            'message' => 'Deleted proxy device',
        ];
    }
}
