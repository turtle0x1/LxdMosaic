<?php

namespace dhope0000\LXDClient\Tools\User\Password;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

class CheckPasswordPolicy
{
    public function __construct(GetSetting $getSetting)
    {
        $this->getSetting = $getSetting;
    }

    public function conforms($pwd) :void
    {
        if ((bool) $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::STRONG_PASSWORD_POLICY) === false) {
            return;
        }

        if (strlen($pwd) < 8) {
            throw new \Exception("Password too short!");
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            throw new \Exception("Password must include at least one number!");
        }

        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            throw new \Exception("Password must include at least one letter!");
        }
    }
}
