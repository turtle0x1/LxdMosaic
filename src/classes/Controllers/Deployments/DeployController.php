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
     * @Route("", name="Deploy Deployment")
     */
    public function deploy(int $deploymentId, array $instances)
    {
        $data = $this->deploy->deploy($deploymentId, $instances);
        return ["state"=>"success", "message"=>"Deployment complete", "data"=>$data];
    }
}
