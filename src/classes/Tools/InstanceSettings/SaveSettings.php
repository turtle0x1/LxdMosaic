<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Model\InstanceSettings\InsertSetting;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class SaveSettings
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly InsertSetting $insertSetting
    ) {
    }

    public function save($userId, $settings): bool
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        foreach ($settings as $setting) {
            $this->insertSetting->insert($setting['id'], $setting['value']);
        }
        return true;
    }
}
