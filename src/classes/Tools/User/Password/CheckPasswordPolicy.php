<?php

namespace dhope0000\LXDClient\Tools\User\Password;

use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;

class CheckPasswordPolicy
{
    public function __construct(
        private readonly GetSetting $getSetting
    ) {
    }

    public function conforms($pwd): void
    {
        if ((bool) $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::STRONG_PASSWORD_POLICY) === false) {
            return;
        }

        if (strlen((string) $pwd) < 8) {
            throw new \Exception('Password too short!');
        }

        if (!preg_match('#[0-9]+#', (string) $pwd)) {
            throw new \Exception('Password must include at least one number!');
        }

        if (!preg_match('#[a-zA-Z]+#', (string) $pwd)) {
            throw new \Exception('Password must include at least one letter!');
        }
    }
}
