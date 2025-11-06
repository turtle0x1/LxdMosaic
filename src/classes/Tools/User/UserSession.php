<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\InvalidateToken;
use Symfony\Component\HttpFoundation\Session\Session;

class UserSession
{
    public function __construct(
        private readonly InvalidateToken $invalidateToken,
        private readonly Session $session,
        private readonly ValidateToken $validateToken
    ) {
    }

    public function isLoggedIn()
    {
        return !empty($this->getUserId()) && $this->validateToken->validate(
            $this->getUserId(),
            $this->session->get('wsToken')
        );
    }

    public function logout()
    {
        $this->invalidateToken->invalidate($this->session->get('userId'), $this->session->get('wsToken'));
        $this->session->clear();
        return true;
    }

    public function getToken()
    {
        return $this->session->get('wsToken');
    }

    public function getUserId()
    {
        return $this->session->get('userId');
    }
}
