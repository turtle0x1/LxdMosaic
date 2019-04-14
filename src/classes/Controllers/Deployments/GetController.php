<?php

namespace dhope0000\LXDClient\Controllers\Deployments;

use dhope0000\LXDClient\Tools\Deployments\GetDeployments;

class GetController
{
    public function __construct(GetDeployments $getDeployments)
    {
        $this->getDeployments = $getDeployments;
    }

    public function getAll()
    {
        return $this->getDeployments->getAll();
    }
}
