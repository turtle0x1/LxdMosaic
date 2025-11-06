<?php

namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\SetInstanceSettings;
use Symfony\Component\Routing\Attribute\Route;

class SetSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly SetInstanceSettings $setInstanceSettings
    ) {
    }

    #[Route(path: '/api/Instances/SetSettingsController/set', name: 'Set Instance Settings', methods: ['POST'])]
    public function set(Host $host, string $container, array $settings)
    {
        $this->setInstanceSettings->set($host, $container, $settings);
        return [
            'state' => 'success',
            'message' => 'Updated container settings',
        ];
    }
}
