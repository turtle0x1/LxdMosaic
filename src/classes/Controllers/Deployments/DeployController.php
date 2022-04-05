<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Deploy;
use Symfony\Component\Routing\Annotation\Route;

class DeployController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(Deploy $deploy)
    {
        $this->deploy = $deploy;
    }
    /**
     * @Route("/api/Deployments/DeployController/deploy", methods={"POST"}, name="Deploy Deployment", options={"rbac" = "deployments.deploy"})
     */
    public function deploy(int $userId, int $deploymentId, array $instances)
    {
        $data = $this->deploy->deploy($userId, $deploymentId, $instances);
        return ["state"=>"success", "message"=>"Deployment complete", "data"=>$data];
    }
}
