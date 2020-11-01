<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\GrantAccess;

class GrantAccessController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $grantAccess;

    public function __construct(GrantAccess $grantAccess)
    {
        $this->grantAccess = $grantAccess;
    }

    public function grant(int $userId, int $targetUser, array $hosts, array $projects)
    {
        $this->grantAccess->grant($userId, $targetUser, $hosts, $projects);
        return ["state"=>"success", "message"=>"Granted Access"];
    }
}
