<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\CloudConfig\DeployConfigToContainer;
use Symfony\Component\Routing\Annotation\Route;

class DeployController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeployConfigToContainer $deployConfigToContainer
    ) {
    }

    /**
     * @Route("/api/CloudConfig/DeployController/deploy", name="Deploy Cloud Config", methods={"POST"})
     */
    public function deploy(
        HostsCollection $hosts,
        string $containerName,
        int $cloudConfigId,
        $additionalProfiles = [],
        $gpus = [],
        string $project = ''
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
        return [
            'state' => 'success',
            'message' => 'Begun deploy',
        ];
    }
}
