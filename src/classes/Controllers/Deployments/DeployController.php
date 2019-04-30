<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Deploy;

class DeployController
{
    public function __construct(Deploy $deploy)
    {
        $this->deploy = $deploy;
    }

    public function deploy(int $deploymentId, array $instances)
    {
        $this->deploy->deploy($deploymentId, $instances);
        return ["state"=>"success", "message"=>"Deployment complete"];
    }
}
