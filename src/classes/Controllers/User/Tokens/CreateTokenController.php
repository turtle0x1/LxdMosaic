<?php

namespace dhope0000\LXDClient\Controllers\User\Tokens;

use dhope0000\LXDClient\Model\Users\InsertToken;
use Symfony\Component\Routing\Annotation\Route;

class CreateTokenController
{
    private $insertToken;
    
    public function __construct(InsertToken $insertToken)
    {
        $this->insertToken = $insertToken;
    }

    /**
     * @Route("/api/User/Tokens/CreateTokenController/create", name="api_user_tokens_createtokencontroller_create", methods={"POST"})
     */
    public function create(int $userId, string $token)
    {
        $this->insertToken->insert($userId, $token, 1);
        return ["state"=>"success", "message"=>"Create API permanent key"];
    }
}
