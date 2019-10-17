<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\DeployConfigToContainer;

class DeployController implements \dhope0000\LXDClient\Interfaces\RecordAction
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
            $imageDetails,
            $profileName,
            $additionalProfiles,
            $cloudConfigId
        );
        return ["state"=>"success", "message"=>"Begun deploy"];
    }
}
