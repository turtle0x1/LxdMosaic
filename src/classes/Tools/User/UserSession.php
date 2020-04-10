<?php

namespace dhope0000\LXDClient\Tools\User;

use Symfony\Component\HttpFoundation\Session\Session;
use dhope0000\LXDClient\Model\Users\InvalidateToken;

class UserSession
{
    public function __construct(InvalidateToken $invalidateToken, Session $session)
    {
        $this->invalidateToken = $invalidateToken;
        $this->session = $session;
    }

    public function isLoggedIn()
    {
        return !empty($this->getUserId());
    }

    public function logout()
    {
        $this->invalidateToken->invalidate($this->session->get("userId"), $this->session->get("wsToken"));
        $this->session->clear();
        return true;
    }

    public function isAdminOrThrow()
    {
        if ((bool) $this->session->get("isAdmin") !== true) {
            throw new \Exception("Lacking permision", 1);
        }
        return true;
    }

    public function isAdmin()
    {
        return (bool) $this->session->get("isAdmin");
    }

    public function getToken()
    {
        return $this->session->get("wsToken");
    }

    public function getUserId()
    {
        return $this->session->get("userId");
    }
}
