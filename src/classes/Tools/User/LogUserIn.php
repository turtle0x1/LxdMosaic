<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\InsertToken;
use dhope0000\LXDClient\Tools\Utilities\StringTools;

class LogUserIn
{
    public function __construct(FetchUserDetails $fetchUserDetails, InsertToken $insertToken)
    {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->insertToken = $insertToken;
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

        $_SESSION["userId"] = $userId;
        $_SESSION["isAdmin"] = $this->fetchUserDetails->isAdmin($userId);

        $_SESSION["wsToken"] = StringTools::random(256);
        $this->insertToken->insert($_SESSION["wsToken"], $userId);

        header("Location: /");

        return true;
    }
}
