<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\InsertDeployment;

class CreateDeployment
{
    public function __construct(InsertDeployment $insertDeployment)
    {
        $this->insertDeployment = $insertDeployment;
    }

    public function create(string $name)
    {
        $this->insertDeployment->insert($name);
        $deploymentId = $this->insertDeployment->getDeploymentId();
        return true;
    }
}
