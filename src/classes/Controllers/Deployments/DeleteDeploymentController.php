<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\DeleteDeployment;
use Symfony\Component\Routing\Annotation\Route;

class DeleteDeploymentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteDeployment $deleteDeployment)
    {
        $this->deleteDeployment = $deleteDeployment;
    }
    /**
     * @Route("", name="Delete Deployment")
     */
    public function delete(int $deploymentId)
    {
        $this->deleteDeployment->delete($deploymentId);
        return ["state"=>"success", "message"=>"Deleted deployment"];
    }
}
