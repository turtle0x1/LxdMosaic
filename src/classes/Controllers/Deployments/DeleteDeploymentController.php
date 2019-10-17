<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\DeleteDeployment;

class DeleteDeploymentController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteDeployment $deleteDeployment)
    {
        $this->deleteDeployment = $deleteDeployment;
    }

    public function delete(int $deploymentId)
    {
        $this->deleteDeployment->delete($deploymentId);
        return ["state"=>"success", "message"=>"Deleted deployment"];
    }
}
