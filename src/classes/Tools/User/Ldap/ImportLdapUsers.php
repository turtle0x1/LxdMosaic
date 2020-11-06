<?php

namespace dhope0000\LXDClient\Tools\User\Ldap;

use dhope0000\LXDClient\Model\Users\FetchUsers;
use dhope0000\LXDClient\Model\Users\InsertUser;
use dhope0000\LXDClient\Tools\Ldap\Ldap;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

class ImportLdapUsers
{
    public function __construct(Ldap $ldap, FetchUsers $fetchUsers, InsertUser $insertUser, GetSetting $getSetting)
    {
        $this->ldap = $ldap;
        $this->fetchUsers = $fetchUsers;
        $this->insertUser = $insertUser;
        $this->getSetting = $getSetting;
    }

    public function import()
    {
        $knownLdapIds = $this->fetchUsers->fetchLdapIds();
        $users = $this->findLdapUsers();
        foreach ($users as $ldapId=>$userName) {
            if (in_array($ldapId, $knownLdapIds)) {
                continue;
            }
            //NOTE Generate a random 256 charecter password, not going to be
            //     guessed or used!
            $hash = password_hash(StringTools::random(256), PASSWORD_DEFAULT);
            $this->insertUser->insert($userName, $hash, $ldapId);
        }
        return true;
    }

    private function findLdapUsers()
    {
        $ldapconn = $this->ldap->getAdminBoundConnection();

        $usersOu = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::LDAP_BASE_DN);

        $search_filter = '(objectClass=person)';

        $searchFor = [
            "cn",
            "objectGUID",
            "uid"
        ];

        $result = ldap_search($ldapconn, $usersOu, $search_filter, $searchFor);

        if ($result === false) {
            throw new \Exception("Couldn't search", 1);
        }

        $entries = ldap_get_entries($ldapconn, $result);

        if ($entries["count"] == 0) {
            throw new \Exception("No users found", 1);
        }

        unset($entries["count"]);

        $users = [];
        foreach ($entries as $entry) {
            $linkingId = "";
            if (isset($entry["objectguid"])) {
                $linkingId = bin2hex($entry["objectguid"][0]);
            } elseif (isset($entry["uid"])) {
                $linkingId = $entry["uid"][0];
            }

            $users[$linkingId] = $entry["cn"][0];
        }
        return $users;
    }
}
