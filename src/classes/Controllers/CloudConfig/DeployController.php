<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\DeployConfigToContainer;
use dhope0000\LXDClient\Objects\HostsCollection;

class DeployController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeployConfigToContainer $deploy)
    {
        $this->deployConfigToContainer = $deploy;
    }

    public function deploy(
        HostsCollection $hosts,
        string $containerName,
        int $cloudConfigId,
        array $imageDetails,
        $profileName = "",
        $additionalProfiles = [],
        $gpus = []
    ) {
        $this->deployConfigToContainer->deploy(
            $hosts,
            $containerName,
            $imageDetails,
            $profileName,
            $additionalProfiles,
            $cloudConfigId,
            null,
            $gpus
        );
        return ["state"=>"success", "message"=>"Begun deploy"];
    }
}
