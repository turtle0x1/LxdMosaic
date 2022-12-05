<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchTokens;

class ValidateToken
{
    private FetchTokens $fetchTokens;

    public function __construct(FetchTokens $fetchTokens)
    {
        $this->fetchTokens = $fetchTokens;
    }

    public function validate(int $userId, string $token) :bool
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
