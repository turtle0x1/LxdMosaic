<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\DeployToProfile;
use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Objects\HostsCollection;

class DeployConfigToContainer
{
    public function __construct(
        DeployToProfile $deployToProfile,
        CreateInstance $createInstance
    ) {
        $this->deployToProfile = $deployToProfile;
        $this->createInstance = $createInstance;
    }

    public function deploy(
        HostsCollection $hosts,
        string $containerName,
        array $imageDetails,
        string $profileName = "",
        array $additionalProfiles = [],
        int $cloudConfigId = null,
        int $cloudConfigRevId = null,
        array $gpus = []
    ) {
        if (!is_numeric($cloudConfigId) && !is_numeric($cloudConfigRevId)) {
            throw new \Exception("Please provide cloud config id or a rev id", 1);
        }

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
