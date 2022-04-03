<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\SaveSettings;
use Symfony\Component\Routing\Annotation\Route;

class SaveAllSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $saveSettings;

    public function __construct(SaveSettings $saveSettings)
    {
        $this->saveSettings = $saveSettings;
    }
    /**
     * @Route("/api/InstanceSettings/SaveAllSettingsController/saveAll", methods={"POST"}, name="Save LXDMosaic Settings")
     */
    public function saveAll($userId, $settings)
    {
        $this->saveSettings->save($userId, $settings);
        return ["state"=>"success", "message"=>"Saved Settings"];
    }
}
