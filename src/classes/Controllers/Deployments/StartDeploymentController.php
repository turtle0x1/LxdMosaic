<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\ChangeDeploymentState;
use dhope0000\LXDClient\Constants\StateConstants;
use Symfony\Component\Routing\Annotation\Route;

class StartDeploymentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $changeDeploymentState;
    
    public function __construct(ChangeDeploymentState $changeDeploymentState)
    {
        $this->changeDeploymentState = $changeDeploymentState;
    }
    /**
     * @Route("/api/Deployments/StartDeploymentController/start", name="Start Deployment", methods={"POST"})
     */
    public function start(int $userId, int $deploymentId)
    {
        $this->changeDeploymentState->change($userId, $deploymentId, StateConstants::START);
        return ["state"=>"success", "message"=>"Deployment started"];
    }
}
