<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;
use dhope0000\LXDClient\Constants\StateConstants;
use dhope0000\LXDClient\Tools\Deployments\Containers\SetStartTimes;

class ChangeDeploymentState
{
    public function __construct(
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        GetContainersInDeployment $getContainersInDeployment,
        SetStartTimes $setStartTimes
    ) {
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->getContainersInDeployment = $getContainersInDeployment;
        $this->setStartTimes = $setStartTimes;
    }

    public function change(int $deploymentId, string $state)
    {
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
