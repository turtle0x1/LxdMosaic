<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\DeleteProxyDevice;
use Symfony\Component\Routing\Annotation\Route;

class DeleteProxyDeviceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deleteProxyDevice;
    
    public function __construct(DeleteProxyDevice $deleteProxyDevice)
    {
        $this->deleteProxyDevice = $deleteProxyDevice;
    }
    /**
     * @Route("/api/Hosts/Instances/DeleteProxyDeviceController/delete", name="Delete proxy device", methods={"POST"})
     */
    public function delete(Host $host, string $instance, string $device)
    {
        $this->deleteProxyDevice->delete($host, $instance, $device);
        return ["state"=>"success", "message"=>"Deleted proxy device"];
    }
}
