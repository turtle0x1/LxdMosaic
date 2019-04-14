<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\FetchDeployments;

class GetDeployments
{
    public function __construct(FetchDeployments $fetchDeployments)
    {
        $this->fetchDeployments = $fetchDeployments;
    }

    public function getAll()
    {
        return $this->fetchDeployments->fetchAll();
    }
}
