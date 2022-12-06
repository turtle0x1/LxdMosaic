<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployments;

class GetController
{
    private GetDeployments $getDeployments;

    public function __construct(GetDeployments $getDeployments)
    {
        $this->getDeployments = $getDeployments;
    }

    public function getAll(int $userId) :array
    {
        return $this->getDeployments->getAll($userId);
    }
}
