<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Model\InstanceSettings\GetSettings;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetAllSettingsController
{
    public function __construct(GetSettings $getSettings, ValidatePermissions $validatePermissions)
    {
        $this->getSettings = $getSettings;
        $this->validatePermissions = $validatePermissions;
    }

    public function getAll(int $userId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return $this->getSettings->getAllSettingsWithLatestValues();
    }
}
