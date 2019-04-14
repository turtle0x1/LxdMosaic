<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\CreateDeployment;

class CreateController
{
    public function __construct(CreateDeployment $createDeployment)
    {
        $this->createDeployment = $createDeployment;
    }

    public function create(string $name)
    {
        $this->createDeployment->create($name);
        return ["state"=>"success", "message"=>"Created deployment"];
    }
}
