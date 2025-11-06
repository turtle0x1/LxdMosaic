<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Constants\StateConstants;
use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;
use dhope0000\LXDClient\Tools\Deployments\Containers\SetStartTimes;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;

class ChangeDeploymentState
{
    public function __construct(
        private readonly AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        private readonly HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        private readonly GetContainersInDeployment $getContainersInDeployment,
        private readonly SetStartTimes $setStartTimes
    ) {
    }

    public function change(int $userId, int $deploymentId, string $state)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        $profiles = $this->hostHaveDeploymentProfiles->getAllProfilesInDeployment($deploymentId);

        $containers = $this->getContainersInDeployment->getFromProfile($profiles);

        foreach ($containers as $host) {
            foreach ($host->getCustomProp('containers') as $container) {
                $host->instances->setState($container['name'], $state, 30, true, false, true);

                if ($state == StateConstants::START) {
                    $this->setStartTimes->set($deploymentId, $host->getHostId(), $container['name']);
                }
            }
        }

        return true;
    }
}
