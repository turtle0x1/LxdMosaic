<?php
namespace dhope0000\LXDClient\Controllers\Instances\Settings;

use dhope0000\LXDClient\Model\Containers\Settings\GetSettings;

class GetAllAvailableSettingsController
{
    public function __construct(GetSettings $getSettings)
    {
        $this->getSettings = $getSettings;
    }

    public function getAll()
    {
        return $this->getSettings->getAllEnabledNamesAndDefaults();
    }
}
