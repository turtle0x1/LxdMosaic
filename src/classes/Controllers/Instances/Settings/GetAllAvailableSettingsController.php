<?php
namespace dhope0000\LXDClient\Controllers\Instances\Settings;

use dhope0000\LXDClient\Model\Instances\Settings\GetSettings;
use Symfony\Component\Routing\Annotation\Route;

class GetAllAvailableSettingsController
{
    public function __construct(GetSettings $getSettings)
    {
        $this->getSettings = $getSettings;
    }
    /**
     * @Route("/api/Instances/Settings/GetAllAvailableSettingsController/getAll", methods={"POST"}, name="Get a list of settings we support setting on an instance", options={"rbac" = "instances.settings.upadate"})
     */
    public function getAll()
    {
        return $this->getSettings->getAllEnabledNamesAndDefaults();
    }
}
