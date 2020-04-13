<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\FetchTokens;

class ValidateToken
{
    public function __construct(FetchTokens $fetchTokens)
    {
        $this->fetchTokens = $fetchTokens;
    }

    public function validate(int $userId, string $token)
    {
        $latestToken = $this->fetchTokens->fetchLatestToken($userId);
        return $latestToken === $token;
    }
}
