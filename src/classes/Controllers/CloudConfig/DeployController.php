<?php
namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\DeployConfigToContainer;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class DeployController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeployConfigToContainer $deploy)
    {
        $this->deployConfigToContainer = $deploy;
    }
    /**
     * @Route("", name="Deploy Cloud Config")
     */
    public function deploy(
        HostsCollection $hosts,
        string $containerName,
        int $cloudConfigId,
        $additionalProfiles = [],
        $gpus = [],
        string $project = ""
    ) {
        $response = $this->deployConfigToContainer->deploy(
            $hosts,
            $containerName,
            $additionalProfiles,
            $cloudConfigId,
            null,
            $gpus,
            $project
        );
        return ["state"=>"success", "message"=>"Begun deploy"];
    }
}
