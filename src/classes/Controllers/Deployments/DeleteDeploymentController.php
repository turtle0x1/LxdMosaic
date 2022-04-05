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
     * @Route("/api/Deployments/DeleteDeploymentController/delete", methods={"POST"}, name="Delete Deployment", options={"rbac" = "deployments.delete"})
     */
    public function delete(int $userId, int $deploymentId)
    {
        $this->deleteDeployment->delete($userId, $deploymentId);
        return ["state"=>"success", "message"=>"Deleted deployment"];
    }
}
