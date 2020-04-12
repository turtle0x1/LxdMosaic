<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Instances;

use dhope0000\LXDClient\Tools\Instances\StartInstances;

class StartInstancesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(StartInstances $startInstances)
    {
        $this->startInstances = $startInstances;
    }

    public function start(int $hostId, array $containers)
    {
        $this->startInstances->start($hostId, $containers);
        return ["state"=>"success", "message"=>"Started Containers"];
    }
}
