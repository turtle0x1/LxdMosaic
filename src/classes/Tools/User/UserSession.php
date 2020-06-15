<?php

namespace dhope0000\LXDClient\Tools\User;

use Symfony\Component\HttpFoundation\Session\Session;
use dhope0000\LXDClient\Model\Users\InvalidateToken;
use dhope0000\LXDClient\Tools\User\ValidateToken;

class UserSession
{
    public function __construct(
        InvalidateToken $invalidateToken,
        Session $session,
        ValidateToken $validateToken
    ) {
        $this->invalidateToken = $invalidateToken;
        $this->session = $session;
        $this->validateToken = $validateToken;
    }

    public function isLoggedIn()
    {
        return !empty($this->getUserId()) && $this->validateToken->validate($this->getUserId(), $this->session->get("wsToken"));
    }

    public function logout()
    {
        $this->invalidateToken->invalidate($this->session->get("userId"), $this->session->get("wsToken"));
        $this->session->clear();
        return true;
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
