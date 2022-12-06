<?php

namespace dhope0000\LXDClient\Controllers\User\Tokens;

use dhope0000\LXDClient\Model\Users\InsertToken;

class CreateTokenController
{
    private InsertToken $insertToken;

    public function __construct(InsertToken $insertToken)
    {
        $this->insertToken = $insertToken;
    }

    public function create(int $userId, string $token) :array
    {
        $this->insertToken->insert($userId, $token, 1);
        return ["state"=>"success", "message"=>"Create API permanent key"];
    }
}
