<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Containers\CreateContainer;
use dhope0000\LXDClient\Tools\Utilities\StringTools;

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
        int $cloudConfigId,
        array $imageDetails,
        string $profileName = "",
        array $additionalProfiles = []
    ) {
        if (empty($profileName)) {
            //TODO Generate a random string for profile name
            $profileName = StringTools::random(12);
        }

        $this->deployToProfile->deployToHosts($profileName, $hostUrls, $cloudConfigId);

        return $this->createContainer->create(
            $containerName,
            $additionalProfiles,
            $hostUrls,
            $imageDetails,
            "",
            [$profileName]
        );
    }
}
