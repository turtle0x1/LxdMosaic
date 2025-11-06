<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Model\InstanceSettings\InsertSetting;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\AddHosts;
use dhope0000\LXDClient\Tools\User\ResetPassword;

class FirstRun
{
    private $adminUserId = 1;

    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly AddHosts $addHosts,
        private readonly ResetPassword $resetPassword,
        private readonly InsertSetting $insertSetting
    ) {
    }

    public function run(array $hosts, string $adminPassword, array $settings = [])
    {
        if ($this->fetchUserDetails->adminPasswordBlank() !== true) {
            throw new \Exception('Cant run first run', 1);
        }

        $this->resetPassword->reset($this->adminUserId, $this->adminUserId, $adminPassword);

        foreach ($settings as $setting) {
            $this->validateSetting($setting);
            $this->insertSetting->insert($setting['settingId'], $setting['value']);
        }

        $this->addHosts->add($this->adminUserId, $hosts);
    }

    private function validateSetting(array $setting)
    {
        if (!isset($setting['settingId']) || !is_numeric($setting['settingId'])) {
            throw new \Exception('Setting id missing', 1);
        } elseif (!isset($setting['value']) || $setting['value'] === '') {
            throw new \Exception("A setting is empty that shouldn't be", 1);
        }
    }
}
