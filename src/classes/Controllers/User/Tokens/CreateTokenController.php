<?php

namespace dhope0000\LXDClient\Controllers\User\Tokens;

use dhope0000\LXDClient\Model\Users\InsertToken;
use Symfony\Component\Routing\Annotation\Route;

class CreateTokenController
{
    public function __construct(InsertToken $insertToken)
    {
        $this->insertToken = $insertToken;
    }
    /**
     * @Route("/api/User/Tokens/CreateTokenController/create", methods={"POST"}, name="Create LXDMosaic permanent access token")
     */
    public function create(int $userId, string $token)
    {
        $this->insertToken->insert($userId, $token, 1);
        return ["state"=>"success", "message"=>"Create API permanent key"];
    }
}
