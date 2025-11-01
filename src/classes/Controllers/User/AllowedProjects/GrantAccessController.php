<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\GrantAccess;
use Symfony\Component\Routing\Annotation\Route;

class GrantAccessController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $grantAccess;

    public function __construct(GrantAccess $grantAccess)
    {
        $this->grantAccess = $grantAccess;
    }
    /**
     * @Route("/api/User/AllowedProjects/GrantAccessController/grant", name="Grant user access to a hosts project", methods={"POST"})
     */
    public function grant(int $userId, int $targetUser, array $hosts, array $projects)
    {
        $this->grantAccess->grant($userId, $targetUser, $hosts, $projects);
        return ["state"=>"success", "message"=>"Granted Access"];
    }
}
