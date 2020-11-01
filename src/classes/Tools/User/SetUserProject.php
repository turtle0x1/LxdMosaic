<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\Projects\InsertUserProject;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class SetUserProject
{
    public function __construct(
        InsertUserProject $insertUserProject,
        FetchAllowedProjects $fetchAllowedProjects,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->insertUserProject = $insertUserProject;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function set(int $userId, int $hostId, string $project)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        if (!$isAdmin) {
            $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);
            if (!isset($allowedProjects[$hostId])) {
                throw new \Exception("No access to this host", 1);
            }

            if (!in_array($project, $allowedProjects[$hostId])) {
                throw new \Exception("No access to this project", 1);
            }
        }

        $this->insertUserProject->insert($userId, $hostId, $project);
    }
}
