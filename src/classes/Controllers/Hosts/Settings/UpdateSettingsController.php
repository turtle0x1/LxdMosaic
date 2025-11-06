<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Settings;

use dhope0000\LXDClient\Tools\Hosts\Settings\UpdateHostSettings;
use Symfony\Component\Routing\Attribute\Route;

class UpdateSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly UpdateHostSettings $updateHostSettings
    ) {
    }

    #[Route(path: '/api/Hosts/Settings/UpdateSettingsController/update', name: 'Update hosts lxdmosaic settings', methods: ['POST'])]
    public function update(int $userId, int $hostId, string $alias, int $supportsLoadAverages)
    {
        $this->updateHostSettings->update($userId, $hostId, $alias, $supportsLoadAverages);
        return [
            'state' => 'success',
            'messages' => 'Updated Settings',
        ];
    }
}
