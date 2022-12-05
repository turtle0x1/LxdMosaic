<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\InstanceSettings\InsertSetting;

class SaveSettings
{
    private InsertSetting $insertSetting;
    private ValidatePermissions $validatePermissions;

    public function __construct(ValidatePermissions $validatePermissions, InsertSetting $insertSetting)
    {
        $this->validatePermissions = $validatePermissions;
        $this->insertSetting = $insertSetting;
    }

    public function save($userId, $settings) :bool
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        foreach ($settings as $setting) {
            $this->insertSetting->insert($setting["id"], $setting["value"]);
        }
        return true;
    }
}
