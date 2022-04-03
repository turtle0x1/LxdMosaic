<?php

namespace dhope0000\LXDClient\Controllers\User\Tokens;

use dhope0000\LXDClient\Tools\User\Tokens\DeleteToken;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTokenController
{
    public function __construct(DeleteToken $deleteToken)
    {
        $this->deleteToken = $deleteToken;
    }
    /**
     * @Route("/api/User/Tokens/DeleteTokenController/delete", methods={"POST"}, name="Delete LXDMosaic permanent access token")
     */
    public function delete(int $userId, int $tokenId)
    {
        $this->deleteToken->delete($userId, $tokenId);
        return ["state"=>"success", "message"=>"Deleted token"];
    }
}
