<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\InsertToken;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use Symfony\Component\HttpFoundation\Session\Session;

class LogUserIn
{
    public function __construct(
        FetchUserDetails $fetchUserDetails,
        InsertToken $insertToken,
        Session $session
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->insertToken = $insertToken;
        $this->session = $session;
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

        $token = StringTools::random(256);

        $this->session->set("userId", $userId);
        $this->session->set("isAdmin", $this->fetchUserDetails->isAdmin($userId));
        $this->session->set("wsToken", $token);
        $this->insertToken->insert($userId, $token);

        header("Location: /");

        return true;
    }
}
