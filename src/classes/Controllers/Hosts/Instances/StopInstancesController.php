<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\StopInstances;
use Symfony\Component\Routing\Annotation\Route;

class StopInstancesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $stopInstances;
    
    public function __construct(StopInstances $stopInstances)
    {
        $this->stopInstances = $stopInstances;
    }
    /**
     * @Route("", name="Stop Instances")
     */
    public function stop(Host $host, array $containers)
    {
        $this->stopInstances->stop($host, $containers);
        return ["state"=>"success", "message"=>"Stopped Containers"];
    }
}
