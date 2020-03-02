<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class LogUserIn
{
    public function __construct(FetchUserDetails $fetchUserDetails)
    {
        $this->fetchUserDetails = $fetchUserDetails;
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

        header("Location: /");

        return true;
    }
}
