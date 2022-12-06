<?php

namespace dhope0000\LXDClient\Tools\User\Tokens;

use dhope0000\LXDClient\Model\Users\FetchTokens;
use dhope0000\LXDClient\Model\Users\RemoveToken;

class DeleteToken
{
    private FetchTokens $fetchTokens;
    private RemoveToken $removeToken;

    public function __construct(FetchTokens $fetchTokens, RemoveToken $removeToken)
    {
        $this->fetchTokens = $fetchTokens;
        $this->removeToken = $removeToken;
    }

    public function delete(int $userId, int $tokenId) :bool
    {
        $tokenOwner = $this->fetchTokens->fetchTokenUser($tokenId);

        if ($userId !== (int) $tokenOwner) {
            throw new \Exception("You can't delete other user tokens!", 1);
        }

        $this->removeToken->remove($tokenId);

        return true;
    }
}
