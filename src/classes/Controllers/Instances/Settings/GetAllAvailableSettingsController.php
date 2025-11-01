<?php
namespace dhope0000\LXDClient\Controllers\Instances\Settings;

use dhope0000\LXDClient\Model\Instances\Settings\GetSettings;
use Symfony\Component\Routing\Annotation\Route;

class GetAllAvailableSettingsController
{
    private $getSettings;
    
    public function __construct(GetSettings $getSettings)
    {
        $this->getSettings = $getSettings;
    }

    /**
     * @Route("/api/Instances/Settings/GetAllAvailableSettingsController/getAll", name="api_instances_settings_getallavailablesettingscontroller_getall", methods={"POST"})
     */
    public function getAll()
    {
        return $this->getSettings->getAllEnabledNamesAndDefaults();
    }
}
