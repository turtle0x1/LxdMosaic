<?php

namespace dhope0000\LXDClient\Tools\Deployments\Containers;

use dhope0000\LXDClient\Model\Deployments\Containers\UpdateStartTimes;

class SetStartTimes
{
    public function __construct(
        private readonly UpdateStartTimes $updateStartTimes
    ) {
    }

    public function set($deploymentId, $hostId, $name)
    {
        $this->updateStartTimes->updateFirstStart($deploymentId, $hostId, $name);
        $this->updateStartTimes->updateLastStart($deploymentId, $hostId, $name);
        return true;
    }
}
