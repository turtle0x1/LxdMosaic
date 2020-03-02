<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Tools\User\UserSession;
use dhope0000\LXDClient\Model\InstanceSettings\InsertSetting;

class SaveSettings
{
    private $insertSetting;
    private $userSession;

    public function __construct(UserSession $userSession, InsertSetting $insertSetting)
    {
        $this->userSession = $userSession;
        $this->insertSetting = $insertSetting;
    }

    public function save(array $settings) :bool
    {
        $this->userSession->isAdminOrThrow();

        foreach ($settings as $setting) {
            $this->insertSetting->insert($setting["id"], $setting["value"]);
        }
        return true;
    }
}
