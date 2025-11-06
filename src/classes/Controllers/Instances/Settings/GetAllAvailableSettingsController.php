<?php

namespace dhope0000\LXDClient\Controllers\Instances\Settings;

use dhope0000\LXDClient\Model\Instances\Settings\GetSettings;
use Symfony\Component\Routing\Attribute\Route;

class GetAllAvailableSettingsController
{
    public function __construct(
        private readonly GetSettings $getSettings
    ) {
    }

    #[Route(path: '/api/Instances/Settings/GetAllAvailableSettingsController/getAll', name: 'api_instances_settings_getallavailablesettingscontroller_getall', methods: ['POST'])]
    public function getAll()
    {
        return $this->getSettings->getAllEnabledNamesAndDefaults();
    }
}
