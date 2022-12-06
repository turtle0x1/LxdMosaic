<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings;

use dhope0000\LXDClient\Tools\InstanceSettings\SaveSettings;
use Symfony\Component\Routing\Annotation\Route;

class SaveAllSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private SaveSettings $saveSettings;

    public function __construct(SaveSettings $saveSettings)
    {
        $this->saveSettings = $saveSettings;
    }
    /**
     * @Route("", name="Save LXDMosaic Settings")
     */
    public function saveAll(int $userId, array $settings)
    {
        $this->saveSettings->save($userId, $settings);
        return ["state"=>"success", "message"=>"Saved Settings"];
    }
}
