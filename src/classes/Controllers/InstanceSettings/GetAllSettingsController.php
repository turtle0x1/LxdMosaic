<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Model\InstanceSettings\GetSettings;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Tools\Utilities\IsUpToDate;
use dhope0000\LXDClient\Tools\Utilities\DateTools;
use Symfony\Component\Routing\Annotation\Route;

class GetAllSettingsController
{
    private $getSettings;
    private $validatePermissions;
    private $dateTools;
    
    public function __construct(
        GetSettings $getSettings,
        ValidatePermissions $validatePermissions,
        DateTools $dateTools
    ) {
        $this->getSettings = $getSettings;
        $this->validatePermissions = $validatePermissions;
        $this->dateTools = $dateTools;
    }

    /**
     * @Route("/api/InstanceSettings/GetAllSettingsController/getAll", name="api_instancesettings_getallsettingscontroller_getall", methods={"POST"})
     */
    public function getAll(int $userId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        return [
            "settings"=>$this->getSettings->getAllSettingsWithLatestValues(),
            "versionDetails"=>IsUpToDate::isIt(),
            "timezones"=>$this->dateTools->getTimezoneList()
        ];
    }
}
