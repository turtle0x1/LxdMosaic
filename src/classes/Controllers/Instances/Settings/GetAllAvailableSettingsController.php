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

    #[Route(path: '/api/Instances/Settings/GetAllAvailableSettingsController/getAll', name: 'Get all instance settings', methods: ['POST'])]
    public function getAll()
    {
        return $this->getSettings->getAllEnabledNamesAndDefaults();
    }
}
