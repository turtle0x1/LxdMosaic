<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\CreateDeployment;

class CreateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateDeployment $createDeployment)
    {
        $this->createDeployment = $createDeployment;
    }

    public function create(string $name, array $cloudConfigs)
    {
        $deploymentId = $this->createDeployment->create($name, $cloudConfigs);
        return [
            "state"=>"success",
            "message"=>"Created deployment",
            "deploymentId"=>$deploymentId
        ];
    }
}
