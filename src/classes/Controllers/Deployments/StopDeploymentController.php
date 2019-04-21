<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\ChangeDeploymentState;
use dhope0000\LXDClient\Constants\StateConstants;

class StopDeploymentController
{
    public function __construct(ChangeDeploymentState $changeDeploymentState)
    {
        $this->changeDeploymentState = $changeDeploymentState;
    }

    public function stop(int $deploymentId)
    {
        $this->changeDeploymentState->change($deploymentId, StateConstants::STOP);
        return ["state"=>"success", "message"=>"Deployment started"];
    }
}
