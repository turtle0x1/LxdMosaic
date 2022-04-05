<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\RevokeAccess;
use Symfony\Component\Routing\Annotation\Route;

class RevokeAccessController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $revokeAccess;

    public function __construct(RevokeAccess $revokeAccess)
    {
        $this->revokeAccess = $revokeAccess;
    }
    /**
     * @Route("/api/User/AllowedProjects/RevokeAccessController/revoke", methods={"POST"}, name="Revoke a users access from a hosts project", options={"rbac" = "lxdmosaic.user.access.revoke"})
     */
    public function revoke(int $userId, int $targetUser, int $hostId, string $project)
    {
        $this->revokeAccess->revoke($userId, $targetUser, $hostId, $project);
        return ["state"=>"success", "message"=>"Revoked Access"];
    }
}
