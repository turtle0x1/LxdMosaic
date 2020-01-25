<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Model\Deployments\CloudConfig\FetchCloudConfigs;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;
use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Constants\StateConstants;
use dhope0000\LXDClient\Tools\Deployments\Containers\SetStartTimes;

class ChangeDeploymentState
{
    public function __construct(
        FetchCloudConfigs $fetchCloudConfigs,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        GetContainersInDeployment $getContainersInDeployment,
        LxdClient $lxdClient,
        SetStartTimes $setStartTimes
    ) {
        $this->fetchCloudConfigs = $fetchCloudConfigs;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->getContainersInDeployment = $getContainersInDeployment;
        $this->client = $lxdClient;
        $this->setStartTimes = $setStartTimes;
    }

    public function change(int $deploymentId, string $state)
    {
        $profiles = $this->hostHaveDeploymentProfiles->getAllProfilesInDeployment($deploymentId);

        $containers = $this->getContainersInDeployment->getFromProfile($profiles);

        foreach ($containers as $host => $details) {
            $client = $this->client->getANewClient($details["hostId"]);
            foreach ($details["containers"] as $container) {
                $client->instances->setState($container["name"], $state, 30, true, false, true);

                if (StateConstants::START == $state) {
                    $this->setStartTimes->set(
                        $deploymentId,
                        $details["hostId"],
                        $container["name"]
                    );
                }
            }
        }

        return true;
    }
}
