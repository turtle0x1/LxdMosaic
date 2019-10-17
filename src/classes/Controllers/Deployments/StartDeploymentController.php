<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\ChangeDeploymentState;
use dhope0000\LXDClient\Constants\StateConstants;

class StartDeploymentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(ChangeDeploymentState $changeDeploymentState)
    {
        $this->changeDeploymentState = $changeDeploymentState;
    }

    public function start(int $deploymentId)
    {
        $this->changeDeploymentState->change($deploymentId, StateConstants::START);
        return ["state"=>"success", "message"=>"Deployment started"];
    }
}
