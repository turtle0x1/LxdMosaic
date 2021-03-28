<?php

namespace dhope0000\LXDClient\Tools\Ldap;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Exceptions\Users\CantFindUserException;
use dhope0000\LXDClient\Exceptions\Users\PasswordIncorrectException;

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

    /**
     * Returns username that can be looked up in the DB
     */
    public function verifyAccount($con, $username, $password) :string
    {
        $baseDn = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::LDAP_BASE_DN);
        $filter = "(|(cn=$username,{$baseDn})(mail=$username))"; // where to look for specified "user name"

        $attrs = array("dn", "cn");
        $search = ldap_search($con, $baseDn, $filter, $attrs);
        $entries = ldap_get_entries($con, $search);
        if ($entries["count"] == 0) {
            throw new CantFindUserException($username);
        }

        $bind = @ldap_bind($con, $entries[0]["dn"], $password);

        if (!$bind) {
            throw new PasswordIncorrectException($username);
        }

        // Return a CN we can look up in the DB incase user logged in with
        // a mail account
        return $entries[0]["cn"][0];
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
