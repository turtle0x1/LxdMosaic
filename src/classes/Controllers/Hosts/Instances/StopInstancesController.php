<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Instances\StopInstances;

class StopInstancesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(StopInstances $stopInstances)
    {
        $this->stopInstances = $stopInstances;
    }
    
    public function stop(int $hostId, array $containers)
    {
        $this->stopInstances->stop($hostId, $containers);
        return ["state"=>"success", "message"=>"Stopped Containers"];
    }
}
