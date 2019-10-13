<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Model\InstanceSettings\InsertSetting;

class SaveSettings
{
    private $insertSetting;

    public function __construct(InsertSetting $insertSetting)
    {
        $this->insertSetting = $insertSetting;
    }

    public function save(array $settings) :bool
    {
        foreach ($settings as $setting) {
            $this->insertSetting->insert($setting["id"], $setting["value"]);
        }
        return true;
    }
}
