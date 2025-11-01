<?php

namespace dhope0000\LXDClient\Controllers\User\AllowedProjects;

use dhope0000\LXDClient\Tools\User\AllowedProjects\GrantAccessToProject;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GrantAccessToProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $grantAccessToProject;

    public function __construct(GrantAccessToProject $grantAccessToProject)
    {
        $this->grantAccessToProject = $grantAccessToProject;
    }
    /**
     * @Route("/api/User/AllowedProjects/GrantAccessToProjectController/grant", name="Grant a user access to one hosts project", methods={"POST"})
     */
    public function grant(int $userId, array $targetUsers, Host $host, string $project)
    {
        $this->grantAccessToProject->grant($userId, $targetUsers, $host, $project);
        return ["state"=>"success", "message"=>"Granted Access"];
    }
}
