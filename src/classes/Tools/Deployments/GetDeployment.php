<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\CloudConfig\FetchCloudConfigs;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;
use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInformation;

class GetDeployment
{
    public function __construct(
        FetchCloudConfigs $fetchCloudConfigs,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        GetContainersInDeployment $getContainersInDeployment,
        FetchDeployments $fetchDeployments,
        GetContainersInformation $getContainersInformation
    ) {
        $this->fetchCloudConfigs = $fetchCloudConfigs;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->getContainersInDeployment = $getContainersInDeployment;
        $this->fetchDeployments = $fetchDeployments;
        $this->getContainersInformation = $getContainersInformation;
    }

    public function get(int $deploymentId)
    {
        $output = [];

        $profiles = $this->hostHaveDeploymentProfiles->getAllProfilesInDeployment($deploymentId);

        $containers = $this->getContainersInDeployment->getFromProfile($profiles);
        $containerInfo = $this->getContainersInformation->getContainersInDeployment($deploymentId);

        $containers = $this->addAdditionalInfoToContainers($containers, $containerInfo);

        $output["details"] = $this->fetchDeployments->fetch($deploymentId);
        $output["cloudConfigs"] = $this->fetchCloudConfigs->getAll($deploymentId);
        $output["profiles"] = $profiles;
        $output["containers"] = $containers;
        return $output;
    }

    private function addAdditionalInfoToContainers($containers, array $containerInfo)
    {
        foreach ($containers as $hostName => $hostDetails) {
            foreach ($containerInfo as $info) {
                if ($hostDetails["hostId"] == $info["hostId"]) {
                    foreach ($hostDetails["containers"] as $index => $container) {
                        if ($container["name"] == $info["name"]) {
                            $containers[$hostName]["containers"][$index]["mosaicInfo"] =
                            array_merge($container, $info);
                        }
                    }
                }
            }
        }
        return $containers;
    }
}
