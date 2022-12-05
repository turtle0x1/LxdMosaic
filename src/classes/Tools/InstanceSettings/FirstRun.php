<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\AddHosts;
use dhope0000\LXDClient\Tools\User\ResetPassword;
use dhope0000\LXDClient\Model\InstanceSettings\InsertSetting;

class FirstRun
{
    private FetchUserDetails $fetchUserDetails;
    private AddHosts $addHosts;
    private ResetPassword $resetPassword;
    private InsertSetting $insertSetting;

    private int $adminUserId = 1;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        AddHosts $addHosts,
        ResetPassword $resetPassword,
        InsertSetting $insertSetting
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->addHosts = $addHosts;
        $this->resetPassword = $resetPassword;
        $this->insertSetting = $insertSetting;
    }

    public function run(array $hosts, string $adminPassword, array $settings = []) :void
    {
        if ($this->fetchUserDetails->adminPasswordBlank() !== true) {
            throw new \Exception("Cant run first run", 1);
        }

        $this->resetPassword->reset($this->adminUserId, $this->adminUserId, $adminPassword);

        foreach ($settings as $setting) {
            $this->validateSetting($setting);
            $this->insertSetting->insert($setting["settingId"], $setting["value"]);
        }

        $this->addHosts->add($this->adminUserId, $hosts);
    }

    private function validateSetting(array $setting)
    {
        if (!isset($setting["settingId"]) || !is_numeric($setting["settingId"])) {
            throw new \Exception("Setting id missing", 1);
        } elseif (!isset($setting["value"]) || $setting["value"] === "") {
            throw new \Exception("A setting is empty that shouldn't be", 1);
        }
    }
}
