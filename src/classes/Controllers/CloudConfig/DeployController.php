<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\Deploy;

class DeployController
{
    public function __construct(Deploy $deploy)
    {
        $this->deploy = $deploy;
    }

    public function deploy(
        array $hosts,
        string $containerName,
        int $cloudConfigId,
        array $imageDetails,
        $profileName = "",
        $additionalProfiles = []
    ) {
        $this->deploy->deploy(
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
