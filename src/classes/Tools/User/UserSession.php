<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\InvalidateToken;

class UserSession
{
    public function __construct(InvalidateToken $invalidateToken)
    {
        $this->invalidateToken = $invalidateToken;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION["userId"]) && !empty($_SESSION["userId"]);
    }

    public function logout()
    {
        $this->invalidateToken->invalidate($_SESSION["userId"]);

        $_SESSION = [];
        return true;
    }

    public function isAdminOrThrow()
    {
        if ((bool) $_SESSION["isAdmin"] !== true) {
            throw new \Exception("Lacking permision", 1);
        }
        return true;
    }

    public function isAdmin()
    {
        return (bool) $_SESSION["isAdmin"];
    }
}
