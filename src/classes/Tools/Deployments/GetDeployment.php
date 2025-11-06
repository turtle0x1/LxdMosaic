<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\CloudConfig\FetchCloudConfigs;
use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Model\Deployments\Projects\FetchDeploymentProjects;
use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInformation;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;

class GetDeployment
{
    public function __construct(
        private readonly AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        private readonly FetchCloudConfigs $fetchCloudConfigs,
        private readonly HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        private readonly GetContainersInDeployment $getContainersInDeployment,
        private readonly FetchDeployments $fetchDeployments,
        private readonly GetContainersInformation $getContainersInformation,
        private readonly FetchDeploymentProjects $fetchDeploymentProjects
    ) {
    }

    public function get(int $userId, int $deploymentId)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        $output = [];

        $profiles = $this->hostHaveDeploymentProfiles->getAllProfilesInDeployment($deploymentId);

        $hostWithContainers = $this->getContainersInDeployment->getFromProfile($profiles);
        $containerInfo = $this->getContainersInformation->getContainersInDeployment($deploymentId);

        $hostWithContainers = $this->addAdditionalInfoToContainers($hostWithContainers, $containerInfo);

        $output['details'] = $this->fetchDeployments->fetch($deploymentId);
        $output['cloudConfigs'] = $this->fetchCloudConfigs->getAll($deploymentId);
        $output['profiles'] = $profiles;
        $output['containers'] = $hostWithContainers;
        $output['projects'] = $this->fetchDeploymentProjects->fetchAll($deploymentId);
        return $output;
    }

    private function addAdditionalInfoToContainers($hostWithContainers, array $containerInfo)
    {
        foreach ($hostWithContainers as $host) {
            if (!$host->hostOnline()) {
                continue;
            }
            $containers = $host->getCustomProp('containers');
            foreach ($containerInfo as $info) {
                if ($host->getHostId() == $info['hostId']) {
                    foreach ($containers as $index => &$container) {
                        if ($container['name'] == $info['name']) {
                            $container['mosaicInfo'] = array_merge($container, $info);
                        }
                    }
                }
            }
            $containers = $host->setCustomProp('containers', $containers);
        }
        return $hostWithContainers;
    }
}
