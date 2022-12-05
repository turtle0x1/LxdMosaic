<?php

namespace dhope0000\LXDClient\Tools\Deployments;

use dhope0000\LXDClient\Tools\Deployments\Authorise\AuthoriseDeploymentAccess;
use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Tools\Deployments\Profiles\HostHaveDeploymentProfiles;
use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use dhope0000\LXDClient\Model\CloudConfig\GetConfig;
use dhope0000\LXDClient\Tools\Deployments\Containers\StoreDeployedContainerNames;
use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Utilities\ValidateInstanceName;

class Deploy
{
    private AuthoriseDeploymentAccess $authoriseDeploymentAccess;
    private DeployToProfile $deployToProfile;
    private HostHaveDeploymentProfiles $hostHaveDeploymentProfiles;
    private CreateInstance $createInstance;
    private GetConfig $getConfig;
    private StoreDeployedContainerNames $storeDeployedContainerNames;
    private GetDetails $getDetails;

    public function __construct(
        AuthoriseDeploymentAccess $authoriseDeploymentAccess,
        DeployToProfile $deployToProfile,
        HostHaveDeploymentProfiles $hostHaveDeploymentProfiles,
        CreateInstance $createInstance,
        GetConfig $getConfig,
        StoreDeployedContainerNames $storeDeployedContainerNames,
        GetDetails $getDetails
    ) {
        $this->authoriseDeploymentAccess = $authoriseDeploymentAccess;
        $this->deployToProfile = $deployToProfile;
        $this->hostHaveDeploymentProfiles = $hostHaveDeploymentProfiles;
        $this->createInstance = $createInstance;
        $this->getConfig = $getConfig;
        $this->storeDeployedContainerNames = $storeDeployedContainerNames;
        $this->getDetails = $getDetails;
    }

    public function deploy(int $userId, int $deploymentId, array $instances) :array
    {
        $this->authoriseDeploymentAccess->authorise($userId, $deploymentId);
        $this->validateInstances($instances);

        $revIds = array_column($instances, "revId");
        $imageDetails = $this->getImageDetails($revIds);

        $revProfileNames = [];

        $deployedContainerInformation = [];

        foreach ($instances as $instance) {
            foreach ($instance["hosts"] as $hostId) {
                $hostId = (int) $hostId;
                $host = $this->getDetails->fetchHost($hostId);
                $profile = $this->hostHaveDeploymentProfiles->getProfileName(
                    $host,
                    $deploymentId,
                    $instance["revId"]
                );

                $hostCollection = new HostsCollection([$host]);

                if (!$profile && !isset($revProfileNames[$instance["revId"]])) {
                    $profile = $this->deployProfile($hostCollection, $deploymentId, $instance["revId"]);
                    $revProfileNames[$instance["revId"]] = $profile;
                }

                $profiles = [$profile];

                if (isset($instance["extraProfiles"])) {
                    $profiles = array_merge($profiles, $instance["extraProfiles"]);
                }

                for ($i = 0; $i < $instance["qty"]; $i++) {
                    $containerName = $this->generateInstanceName();

                    $this->createInstance->create(
                        LxdInstanceTypes::CONTAINER,
                        $containerName,
                        $profiles,
                        $hostCollection,
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

    private function deployProfile(HostsCollection $hostCollection, int $deploymentId, int $revId) :string
    {
        $profileName = StringTools::random(12);
        $this->deployToProfile->deployToHosts(
            $profileName,
            $hostCollection,
            null,
            $revId,
            [
                "environment.deploymentId"=>"$deploymentId",
                "environment.revId"=>"$revId"
            ]
        );
        return $profileName;
    }

    public function getImageDetails($revIds) :array
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

    private function generateInstanceName() :string
    {
        try {
            $name = StringTools::random(12);
            ValidateInstanceName::validate($name);
            return $name;
        } catch (\Throwable $e) {
            //NOTE this could infinetly recurse but its so unlikely and PHP
            // will stop it at like 30~ calls
            return $this->generateInstanceName();
        }
    }

    public function validateInstances(array $instances) :bool
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
