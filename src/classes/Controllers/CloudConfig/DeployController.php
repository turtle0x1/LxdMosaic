<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\DeployConfigToContainer;

class DeployController
{
    public function __construct(DeployConfigToContainer $deploy)
    {
        $this->deployConfigToContainer = $deploy;
    }

    public function deploy(
        array $hosts,
        string $containerName,
        int $cloudConfigId,
        array $imageDetails,
        $profileName = "",
        $additionalProfiles = []
    ) {
        $this->deployConfigToContainer->deploy(
            $hosts,
            $containerName,
            $cloudConfigId,
            $imageDetails,
            $profileName,
            $additionalProfiles
        );
        return ["state"=>"success", "message"=>"Begun deploy"];
    }
}
