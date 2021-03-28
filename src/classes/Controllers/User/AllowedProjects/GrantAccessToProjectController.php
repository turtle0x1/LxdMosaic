<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\GrantAccessToProject;
use dhope0000\LXDClient\Objects\Host;

class GrantAccessToProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $grantAccessToProject;

    public function __construct(GrantAccessToProject $grantAccessToProject)
    {
        $this->grantAccessToProject = $grantAccessToProject;
    }

    public function grant(int $userId, array $targetUsers, Host $host, string $project)
    {
        $this->grantAccessToProject->grant($userId, $targetUsers, $host, $project);
        return ["state"=>"success", "message"=>"Granted Access"];
    }
}
