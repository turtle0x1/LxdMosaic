<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;
use dhope0000\LXDClient\Constants\StateConstants;
use dhope0000\LXDClient\Tools\Deployments\Containers\SetStartTimes;

class ChangeDeploymentState
{
    private AuthoriseDeploymentAccess $authoriseDeploymentAccess;
    private HostHaveDeploymentProfiles $hostHaveDeploymentProfiles;
    private GetContainersInDeployment $getContainersInDeployment;
    private SetStartTimes $setStartTimes;

    public function __construct(
        AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        GetContainersInDeployment $getContainersInDeployment,
        SetStartTimes $setStartTimes
    ) {
        $this->authoriseDeploymentAccess = $authoriseDeploymentAccess;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->getContainersInDeployment = $getContainersInDeployment;
        $this->setStartTimes = $setStartTimes;
    }

    public function change(int $userId, int $deploymentId, string $state)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        $profiles = $this->hostHaveDeploymentProfiles->getAllProfilesInDeployment($deploymentId);

        $containers = $this->getContainersInDeployment->getFromProfile($profiles);

        foreach ($containers as $host) {
            foreach ($host->getCustomProp("containers") as $container) {
                $host->instances->setState($container["name"], $state, 30, true, false, true);

                if (StateConstants::START == $state) {
                    $this->setStartTimes->set(
                        $deploymentId,
                        $host->getHostId(),
                        $container["name"]
                    );
                }
            }
        }

        return true;
    }
}
