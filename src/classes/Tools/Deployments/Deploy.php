<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Containers\CreateContainer;

class Deploy
{
    public function __construct(
        DeployToProfile $deployToProfile,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        CreateContainer $createContainer
    ) {
        $this->deployToProfile = $deployToProfile;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->createContainer = $createContainer;
    }
    /**
     * @TODO Get hosts to deploy on
     */
    public function deploy(int $deploymentId, array $instances)
    {
        $this->validateInstances($instances);

        $revProfileNames = [];

        foreach ($instances as $instance) {
            foreach ($instance["hosts"] as $hostId) {
                $hostId = (int) $hostId;
                $profile = $this->hostHaveDeploymentProfiles->getProfileName(
                    $hostId,
                    $deploymentId,
                    $instance["revId"]
                );

                if (!$profile && !isset($revProfileNames[$instance["revId"]])) {
                    $profile = $this->deployProfile($hostId, $deploymentId, $instance["revId"]);
                }

                $profiles = [$profile];

                if (isset($instance["extraProfiles"])) {
                    $profiles = array_merge($profiles, $instance["extraProfiles"]);
                }

                for ($i = 0; $i < $instance["qty"]; $i++) {
                    $containerName = StringTools::random(12);

                    $this->createContainer->create(
                        $containerName,
                        $profiles,
                        [$hostId],
                        $instance["image"]
                    );
                }
            }
        }
    }

    private function deployProfile(int $hostId, int $deploymentId, int $revId)
    {
        $profileName = StringTools::random(12);
        $revProfileNames[$instance["revId"]] = $profileName;
        $this->deployToProfile->deployToHosts(
            $profileName,
            [$hostId],
            null,
            $revId,
            [
                "environment.deploymentId"=>"$deploymentId",
                "environment.revId"=>"$revId"
            ]
        );
        return $profileName;
    }

    public function validateInstances(array $instances)
    {
        foreach ($instances as $instance) {
            if (!isset($instance["revId"]) || !is_numeric($instance["revId"])) {
                throw new \Exception("Missing rev id", 1);
            } elseif (!isset($instance["qty"]) || !is_numeric($instance["qty"])) {
                throw new \Exception("Missing the number of instances", 1);
            } elseif (!isset($instance["image"]) && !is_array($instance["image"])) {
                throw new \Exception("Missing image details", 1);
            } elseif (isset($instance["extraProfiles"]) && !is_array($instance["extraProfiles"])) {
                throw new \Exception("If extra profiles are included its needs to be an array", 1);
            } elseif (isset($instance["hosts"]) && !is_array($instance["hosts"])) {
                throw new \Exception("Must provide host for instance", 1);
            }
        }
        return true;
    }
}
