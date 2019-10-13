<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Model\InstanceSettings\GetSettings;

class GetAllSettingsController
{
    public function __construct(GetSettings $getSettings)
    {
        $this->getSettings = $getSettings;
    }

    public function getAll()
    {
        return $this->getSettings->getAllSettingsWithLatestValues();
    }
}
