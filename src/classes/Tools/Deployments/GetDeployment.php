<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\CloudConfig\FetchCloudConfigs;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;

class GetDeployment
{
    public function __construct(
        FetchCloudConfigs $fetchCloudConfigs,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        GetContainersInDeployment $getContainersInDeployment
    ) {
        $this->fetchCloudConfigs = $fetchCloudConfigs;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->getContainersInDeployment = $getContainersInDeployment;
    }

    public function get(int $deploymentId)
    {
        $output = [];

        $profiles = $this->hostHaveDeploymentProfiles->getAllProfilesInDeployment($deploymentId);

        $output["cloudConfigs"] = $this->fetchCloudConfigs->getAll($deploymentId);
        $output["profiles"] = $profiles;
        $output["containers"] = $this->getContainersInDeployment->getFromProfile($profiles);
        return $output;
    }
}
