<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Settings;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class UpdateHostSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    /**
     * @Route("/api/Hosts/Settings/UpdateHostSettingsController/update", name="Update hosts config settings", methods={"POST"})
     */
    public function update($userId, Host $host, array $settings)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $info = $host->host->info();
        foreach ($settings as $key => $value) {
            $info['config'][$key] = $value;
        }
        $host->host->replace($info['config']);
        return [
            'state' => 'success',
            'messages' => 'Updated LXD Settings',
        ];
    }
}
