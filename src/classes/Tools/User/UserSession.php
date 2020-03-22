<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\UpdateToken;
use dhope0000\LXDClient\Model\Database\Database;

class UserSession
{
    public function isLoggedIn()
    {
        return isset($_SESSION["userId"]) && !empty($_SESSION["userId"]);
    }

    public function logout()
    {
        $updateToken = new UpdateToken(new Database);
        $updateToken->update($_SESSION["userId"]);

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
