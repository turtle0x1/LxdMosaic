<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use dhope0000\LXDClient\Model\CloudConfig\GetConfig;
use dhope0000\LXDClient\Tools\InstanceSettings\CreatePhoneHomeVendorString;
use dhope0000\LXDClient\Tools\Deployments\Containers\StoreDeployedContainerNames;
use dhope0000\LXDClient\Constants\LxdInstanceTypes;

class Deploy
{
    public function __construct(
        DeployToProfile $deployToProfile,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        CreateInstance $createInstance,
        GetConfig $getConfig,
        CreatePhoneHomeVendorString $createPhoneHomeVendorString,
        StoreDeployedContainerNames $storeDeployedContainerNames
    ) {
        $this->deployToProfile = $deployToProfile;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->createInstance = $createInstance;
        $this->getConfig = $getConfig;
        $this->createPhoneHomeVendorString = $createPhoneHomeVendorString;
        $this->storeDeployedContainerNames = $storeDeployedContainerNames;
    }

    public function deploy(int $deploymentId, array $instances)
    {
        $this->validateInstances($instances);

        $revIds = array_column($instances, "revId");
        $imageDetails = $this->getImageDetails($revIds);

        $revProfileNames = [];

        $deployedContainerInformation = [];
        $vendorData = $this->createPhoneHomeVendorString->create($deploymentId);

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

                    $this->createInstance->create(
                        LxdInstanceTypes::CONTAINER,
                        $containerName,
                        $profiles,
                        [$hostId],
                        $imageDetails[$instance["revId"]]
                    );

                    $deployedContainerInformation[] = [
                        "hostId"=>$hostId,
                        "name"=>$containerName
                    ];
                }
            }
        }

        $this->storeDeployedContainerNames->store(
            $deploymentId,
            $deployedContainerInformation
        );

        return [
            "deployedContainerInformation"=>$deployedContainerInformation
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
