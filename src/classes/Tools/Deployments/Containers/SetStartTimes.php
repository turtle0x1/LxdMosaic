<?php

namespace dhope0000\LXDClient\Tools\Deployments\Containers;

use dhope0000\LXDClient\Model\Deployments\Containers\UpdateStartTimes;

class SetStartTimes
{
    private $updateStartTimes;
    
    public function __construct(UpdateStartTimes $updateStartTimes)
    {
        $this->updateStartTimes = $updateStartTimes;
    }

    public function set($deploymentId, $hostId, $name)
    {
        $this->updateStartTimes->updateFirstStart($deploymentId, $hostId, $name);
        $this->updateStartTimes->updateLastStart($deploymentId, $hostId, $name);
        return true;
    }
}
