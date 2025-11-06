<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\RevokeAccess;
use Symfony\Component\Routing\Attribute\Route;

class RevokeAccessController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly RevokeAccess $revokeAccess
    ) {
    }

    #[Route(path: '/api/User/AllowedProjects/RevokeAccessController/revoke', name: 'Revoke a users access from a hosts project', methods: ['POST'])]
    public function revoke(int $userId, int $targetUser, int $hostId, string $project)
    {
        $this->revokeAccess->revoke($userId, $targetUser, $hostId, $project);
        return [
            'state' => 'success',
            'message' => 'Revoked Access',
        ];
    }
}
