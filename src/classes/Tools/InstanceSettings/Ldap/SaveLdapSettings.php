<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\Ldap;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Model\InstanceSettings\InsertSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Tools\Ldap\Ldap;

class SaveLdapSettings
{
    private $insertSetting;
    private $validatePermissions;

    public function __construct(ValidatePermissions $validatePermissions, InsertSetting $insertSetting, Ldap $ldap)
    {
        $this->validatePermissions = $validatePermissions;
        $this->insertSetting = $insertSetting;
        $this->ldap = $ldap;
    }

    public function save($userId, $settings) :bool
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        $expectedSettingIds = [
            InstanceSettingsKeys::LDAP_SERVER,
            InstanceSettingsKeys::LDAP_LOOKUP_USER,
            InstanceSettingsKeys::LDAP_LOOKUP_USER_PASS,
            InstanceSettingsKeys::LDAP_BASE_DN
        ];

        foreach ($settings as $i => $setting) {
            if (!in_array($setting["id"], $expectedSettingIds)) {
                throw new \Exception("not expeting setting id {$setting["id"]}", 1);
            }
            unset($settings[$i]);
            $settings[$setting["id"]] = $setting["value"];
        }

        if (empty($settings[InstanceSettingsKeys::LDAP_SERVER])) {
            $this->insertSetting->insert(InstanceSettingsKeys::LDAP_SERVER, "");
            return true;
        }

        // Validate the provided settings no point storing junk
        $con = $this->ldap->getConnectionOrThrow($settings[InstanceSettingsKeys::LDAP_SERVER]);

        $this->ldap->bindLdapOrThrow(
            $con,
            $settings[InstanceSettingsKeys::LDAP_LOOKUP_USER],
            $settings[InstanceSettingsKeys::LDAP_LOOKUP_USER_PASS]
        );

        foreach ($settings as $id => $value) {
            $this->insertSetting->insert($id, $value);
        }

        return true;
    }
}
