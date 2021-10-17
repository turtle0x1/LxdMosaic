<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Model\CloudConfig\GetConfig;

class DeployConfigToContainer
{
    public function __construct(
        DeployToProfile $deployToProfile,
        CreateInstance $createInstance,
        GetConfig $getConfig
    ) {
        $this->deployToProfile = $deployToProfile;
        $this->createInstance = $createInstance;
        $this->getConfig = $getConfig;
    }

    public function deploy(
        HostsCollection $hosts,
        string $containerName,
        string $profileName = "",
        array $additionalProfiles = [],
        int $cloudConfigId = null,
        int $cloudConfigRevId = null,
        array $gpus = []
    ) {
        if (!is_numeric($cloudConfigId) && !is_numeric($cloudConfigRevId)) {
            throw new \Exception("Please provide cloud config id or a rev id", 1);
        }

        if (!is_numeric($cloudConfigRevId)) {
            $imageDetails = $this->getConfig->getLatestConfig($cloudConfigId)["imageDetails"];
        } else {
            $imageDetails = $this->getConfig->getLatestConfigByRevId($cloudConfigRevId)["imageDetails"];
        }

        if (empty($imageDetails)) {
            throw new \Exception("Missing image details", 1);
        }

        $imageDetails = json_decode($imageDetails, true)["details"];

        if (empty($profileName)) {
            $profileName = StringTools::random(12);
        }

        $this->deployToProfile->deployToHosts(
            $profileName,
            $hosts,
            $cloudConfigId,
            $cloudConfigRevId
        );

        return $this->createInstance->create(
            LxdInstanceTypes::CONTAINER,
            $containerName,
            $additionalProfiles,
            $hosts,
            $imageDetails,
            "",
            [$profileName],
            "",
            $gpus
        );
    }
}
