<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;
use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Deployments\RemoveDeployment;

class DeleteDeployment
{
    public function __construct(
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        GetContainersInDeployment $getContainersInDeployment,
        LxdClient $lxdClient,
        RemoveDeployment $removeDeployment
    ) {
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->getContainersInDeployment = $getContainersInDeployment;
        $this->client = $lxdClient;
        $this->removeDeployment = $removeDeployment;
    }

    public function delete(int $deploymentId)
    {
        $profiles = $this->hostHaveDeploymentProfiles->getAllProfilesInDeployment($deploymentId);
        $containers = $this->getContainersInDeployment->getFromProfile($profiles);

        foreach ($containers as $host => $details) {
            $client = $this->client->getANewClient($details["hostId"]);
            foreach ($details["containers"] as $container) {
                $client->instances->setState($container["name"], "stop", 30, true, false, true);
                $client->instances->remove($container["name"], true);
            }
        }

        foreach ($profiles as $host => $details) {
            $client = $this->client->getANewClient($details["hostId"]);
            foreach ($details["profiles"] as $profile) {
                $client->profiles->remove($profile["name"]);
            }
        }

        $this->removeDeployment->delete($deploymentId);

        return true;
    }
}
