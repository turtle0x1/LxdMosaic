<?php

namespace dhope0000\LXDClient\Tools\User;

class UserSession
{
    public function isLoggedIn()
    {
        return isset($_SESSION["userId"]) && !empty($_SESSION["userId"]);
    }

    public function logout()
    {
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
