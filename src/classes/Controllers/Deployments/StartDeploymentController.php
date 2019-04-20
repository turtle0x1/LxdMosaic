<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Deploy;

class StartDeploymentController
{
    public function __construct(Deploy $deploy)
    {
        $this->deploy = $deploy;
    }

    public function start(int $deploymentId)
    {
        $this->startDeployment->start($deploymentId);
        return ["state"=>"success", "message"=>"Deployment started"];
    }
}
