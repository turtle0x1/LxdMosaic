<?php
namespace dhope0000\LXDClient\Controllers\Hosts;

use dhope0000\LXDClient\Tools\Hosts\GetInstances;

class GetInstancesController
{
    public function __construct(GetInstances $getInstances)
    {
        $this->getInstances = $getInstances;
    }
    public function get()
    {
        return $this->getInstances->get();
    }
}
