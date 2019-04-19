<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\CreateDeployment;

class CreateController
{
    public function __construct(CreateDeployment $createDeployment)
    {
        $this->createDeployment = $createDeployment;
    }

    public function create(string $name, array $cloudConfigs)
    {
        $this->createDeployment->create($name, $cloudConfigs);
        return ["state"=>"success", "message"=>"Created deployment"];
    }
}
