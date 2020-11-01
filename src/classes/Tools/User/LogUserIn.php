<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\InsertToken;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use Symfony\Component\HttpFoundation\Session\Session;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class LogUserIn
{
    public function __construct(
        FetchUserDetails $fetchUserDetails,
        InsertToken $insertToken,
        Session $session,
        FetchAllowedProjects $fetchAllowedProjects
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->insertToken = $insertToken;
        $this->session = $session;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function login(string $username, string $password)
    {
        $hash = $this->fetchUserDetails->fetchHash($username);

        if (empty($hash)) {
            throw new \Exception("Username not found", 1);
        } elseif (password_verify($password, $hash) !== true) {
            throw new \Exception("Password incorrect", 1);
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
