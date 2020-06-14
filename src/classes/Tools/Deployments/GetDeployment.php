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

        $hostWithContainers = $this->getContainersInDeployment->getFromProfile($profiles);
        $containerInfo = $this->getContainersInformation->getContainersInDeployment($deploymentId);

        $hostWithContainers = $this->addAdditionalInfoToContainers($hostWithContainers, $containerInfo);

        $output["details"] = $this->fetchDeployments->fetch($deploymentId);
        $output["cloudConfigs"] = $this->fetchCloudConfigs->getAll($deploymentId);
        $output["profiles"] = $profiles;
        $output["containers"] = $hostWithContainers;
        return $output;
    }

    private function addAdditionalInfoToContainers($hostWithContainers, array $containerInfo)
    {
        foreach ($hostWithContainers as $host) {
            $containers = $host->getCustomProp("containers");
            foreach ($containerInfo as $info) {
                if ($host->getHostId() == $info["hostId"]) {
                    foreach ($containers as $index => &$container) {
                        if ($container["name"] == $info["name"]) {
                            $container["mosaicInfo"] = array_merge($container, $info);
                        }
                    }
                }
            }
            $containers = $host->setCustomProp("containers", $containers);
        }
        return $hostWithContainers;
    }
}
