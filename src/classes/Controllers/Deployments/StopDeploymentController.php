<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\ChangeDeploymentState;
use dhope0000\LXDClient\Constants\StateConstants;
use Symfony\Component\Routing\Annotation\Route;

class StopDeploymentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $changeDeploymentState;
    
    public function __construct(ChangeDeploymentState $changeDeploymentState)
    {
        $this->changeDeploymentState = $changeDeploymentState;
    }
    /**
     * @Route("/api/Deployments/StopDeploymentController/stop", name="Stop Deployment", methods={"POST"})
     */
    public function stop(int $userId, int $deploymentId)
    {
        $this->changeDeploymentState->change($userId, $deploymentId, StateConstants::STOP);
        return ["state"=>"success", "message"=>"Deployment started"];
    }
}
