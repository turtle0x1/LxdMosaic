<?php

namespace dhope0000\LXDClient\Controllers\User\Tokens;

use dhope0000\LXDClient\Tools\User\Tokens\DeleteToken;

class DeleteTokenController
{
    private $deleteToken;

    public function __construct(DeleteToken $deleteToken)
    {
        $this->deleteToken = $deleteToken;
    }

    public function delete(int $userId, int $tokenId)
    {
        $this->deleteToken->delete($userId, $tokenId);
        return ["state"=>"success", "message"=>"Deleted token"];
    }
}
