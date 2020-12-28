<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\InsertToken;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use Symfony\Component\HttpFoundation\Session\Session;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Tools\Ldap\Ldap;

class LogUserIn
{
    public function __construct(
        FetchUserDetails $fetchUserDetails,
        InsertToken $insertToken,
        Session $session,
        FetchAllowedProjects $fetchAllowedProjects,
        GetSetting $getSetting,
        Ldap $ldap
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->insertToken = $insertToken;
        $this->session = $session;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->getSetting = $getSetting;
        $this->ldap = $ldap;
    }

    public function login(string $username, string $password)
    {
        $ldapId = $this->fetchUserDetails->fetchLdapId($username);

        if (!empty($ldapId)) {
            $server = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::LDAP_SERVER);

            if (empty($server)) {
                throw new \Exception("Contact your admin, login can't be resolved until then!", 1);
            }

            $baseDn = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::LDAP_BASE_DN);
            try {
                $ldapconn = $this->ldap->getAdminBoundConnection($server);
                $this->ldap->bindLdapOrThrow($ldapconn, "cn=$username,{$baseDn}", $password);
            } catch (\Throwable $e) {
                throw new \Exception("Password incorrect", 1);
            }
        } else {
            $hash = $this->fetchUserDetails->fetchHash($username);

            if (empty($hash)) {
                throw new \Exception("Username not found", 1);
            }
            if (password_verify($password, $hash) !== true) {
                throw new \Exception("Password incorrect", 1);
            }
        }



        $userId = $this->fetchUserDetails->fetchId($username);

        $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);

        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        if (empty($allowedProjects) && !$isAdmin) {
            throw new \Exception("No servers / projects assigned, contact your admin!", 1);
        }

        $token = StringTools::random(256);

        $this->session->set("userId", $userId);
        $this->session->set("isAdmin", $isAdmin);
        $this->session->set("wsToken", $token);
        $this->insertToken->insert($userId, $token);

        header("Location: /");

        return true;
    }
}
