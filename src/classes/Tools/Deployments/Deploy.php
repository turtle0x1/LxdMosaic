<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Containers\CreateContainer;
use dhope0000\LXDClient\Model\CloudConfig\GetConfig;
use dhope0000\LXDClient\Tools\InstanceSettings\CreatePhoneHomeVendorString;

class Deploy
{
    public function __construct(
        DeployToProfile $deployToProfile,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        CreateContainer $createContainer,
        GetConfig $getConfig,
        CreatePhoneHomeVendorString $createPhoneHomeVendorString
    ) {
        $this->deployToProfile = $deployToProfile;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->createContainer = $createContainer;
        $this->getConfig = $getConfig;
        $this->createPhoneHomeVendorString = $createPhoneHomeVendorString;
    }

    public function deploy(int $deploymentId, array $instances)
    {
        $this->validateInstances($instances);

        $revIds = array_column($instances, "revId");
        $imageDetails = $this->getImageDetails($revIds);

        $revProfileNames = [];

        $deployedContainerNames = [];
        $vendorData = $this->createPhoneHomeVendorString->create();

        foreach ($instances as $instance) {
            foreach ($instance["hosts"] as $hostId) {
                $hostId = (int) $hostId;
                $profile = $this->hostHaveDeploymentProfiles->getProfileName(
                    $hostId,
                    $deploymentId,
                    $instance["revId"]
                );

                if (!$profile && !isset($revProfileNames[$instance["revId"]])) {
                    $profile = $this->deployProfile($hostId, $deploymentId, $instance["revId"], $vendorData);
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
                        $imageDetails[$instance["revId"]]
                    );

                    $deployedContainerNames[] = $containerName;
                }
            }
        }

        return [
            "deployedContainerNames"=>$deployedContainerNames
        ];
    }

    private function deployProfile(int $hostId, int $deploymentId, int $revId, string $vendorData)
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
            ],
            $vendorData
        );
        return $profileName;
    }

    public function getImageDetails($revIds)
    {
        $imageDetails = [];
        foreach ($revIds as $revId) {
            $details = $this->getConfig->getImageDetailsByRevId($revId);
            if (empty($details)) {
                throw new \Exception("Missing image from cloud config", 1);
            }
            $imageDetails[$revId] = json_decode($details, true)["details"];
        }
        return $imageDetails;
    }

    public function validateInstances(array $instances)
    {
        foreach ($instances as $instance) {
            if (!isset($instance["revId"]) || !is_numeric($instance["revId"])) {
                throw new \Exception("Missing rev id", 1);
            } elseif (!isset($instance["qty"]) || !is_numeric($instance["qty"])) {
                throw new \Exception("Missing the number of instances", 1);
            } elseif (isset($instance["extraProfiles"]) && !is_array($instance["extraProfiles"])) {
                throw new \Exception("If extra profiles are included its needs to be an array", 1);
            } elseif (isset($instance["hosts"]) && !is_array($instance["hosts"])) {
                throw new \Exception("Must provide host for instance", 1);
            }
        }
        return true;
    }
}
