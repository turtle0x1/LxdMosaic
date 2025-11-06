<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchTokens;

class ValidateToken
{
    public function __construct(
        private readonly FetchTokens $fetchTokens
    ) {
    }

    public function validate(int $userId, string $token)
    {
        $latestToken = $this->fetchTokens->fetchLatestNonPermanentToken($userId);

        if ($latestToken === $token) {
            return true;
        }
        $permanentTokens = $this->fetchTokens->fetchPermanentTokens($userId);
        foreach ($permanentTokens as $permaToken) {
            if ($token === $permaToken) {
                return true;
            }
        }
        return false;
    }
}
