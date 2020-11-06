<?php

namespace dhope0000\LXDClient\Tools\Ldap;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

class Ldap
{
    private $getSetting;

    public function __construct(GetSetting $getSetting)
    {
        $this->getSetting = $getSetting;
    }

    public function getConnectionOrThrow($server)
    {
        $connection = ldap_connect($server);

        if (!$connection) {
            throw new \Exception("Couldn't connect to ldap server", 1);
        }

        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);

        return $connection;
    }

    public function bindLdapOrThrow($con, $adminUser, $adminPass)
    {
        $ldapbind = @ldap_bind($con, $adminUser, $adminPass);
        if (!$ldapbind) {
            throw new \Exception("Couldn't login with acount $adminUser", 1);
        }
        return $con;
    }

    public function getAdminBoundConnection()
    {
        $server = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::LDAP_SERVER);
        $adminUser  = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::LDAP_LOOKUP_USER);
        $adminPass = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::LDAP_LOOKUP_USER_PASS);

        $connection = $this->getConnectionOrThrow($server);

        $connection = $this->bindLdapOrThrow($connection, $adminUser, $adminPass);

        return $connection;
    }
}
