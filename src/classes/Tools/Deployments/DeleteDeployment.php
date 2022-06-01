<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Deployments\Containers\GetContainersInDeployment;
use dhope0000\LXDClient\Model\Deployments\RemoveDeployment;

class DeleteDeployment
{
    private $authoriseDeploymentAccess;
    private $hostHaveDeploymentProfiles;
    private $getContainersInDeployment;
    private $removeDeployment;
    
    public function __construct(
        AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        GetContainersInDeployment $getContainersInDeployment,
        RemoveDeployment $removeDeployment
    ) {
        $this->authoriseDeploymentAccess = $authoriseDeploymentAccess;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->getContainersInDeployment = $getContainersInDeployment;
        $this->removeDeployment = $removeDeployment;
    }

    public function delete(int $userId, int $deploymentId)
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        $profiles = $this->hostHaveDeploymentProfiles->getAllProfilesInDeployment($deploymentId);
        $containers = $this->getContainersInDeployment->getFromProfile($profiles);

        foreach ($containers as $host) {
            foreach ($host->getCustomProp("containers") as $container) {
                $host->instances->setState($container["name"], "stop", 30, true, false, true);
                $host->instances->remove($container["name"], true);
            }
        }

        foreach ($profiles as $host) {
            foreach ($host->getCustomProp("profiles") as $profile) {
                $host->profiles->remove($profile["name"]);
            }
        }

        $this->removeDeployment->delete($deploymentId);

        return true;
    }
}
