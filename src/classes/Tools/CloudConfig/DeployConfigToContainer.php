<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Containers\CreateContainer;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Constants\LxdInstanceTypes;

class DeployConfigToContainer
{
    public function __construct(
        DeployToProfile $deployToProfile,
        CreateContainer $createContainer
    ) {
        $this->deployToProfile = $deployToProfile;
        $this->createContainer = $createContainer;
    }

    public function deploy(
        array $hostUrls,
        string $containerName,
        array $imageDetails,
        string $profileName = "",
        array $additionalProfiles = [],
        int $cloudConfigId = null,
        int $cloudConfigRevId = null
    ) {
        if (!is_numeric($cloudConfigId) && !is_numeric($cloudConfigRevId)) {
            throw new \Exception("Please provide cloud config id or a rev id", 1);
        }

        if (empty($profileName)) {
            $profileName = StringTools::random(12);
        }

        $this->deployToProfile->deployToHosts(
            $profileName,
            $hostUrls,
            $cloudConfigId,
            $cloudConfigRevId
        );

        return $this->createContainer->create(
            LxdInstanceTypes::CONTAINER,
            $containerName,
            $additionalProfiles,
            $hostUrls,
            $imageDetails,
            "",
            [$profileName]
        );
    }
}
