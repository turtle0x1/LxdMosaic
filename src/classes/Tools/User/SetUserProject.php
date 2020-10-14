<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\Projects\InsertUserProject;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class SetUserProject
{
    public function __construct(InsertUserProject $insertUserProject, FetchAllowedProjects $fetchAllowedProjects)
    {
        $this->insertUserProject = $insertUserProject;
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function set(int $userId, int $hostId, string $project)
    {
        $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);

        if (!isset($allowedProjects[$hostId])) {
            throw new \Exception("No access to this host", 1);
        }

        if (!in_array($project, $allowedProjects[$hostId])) {
            throw new \Exception("No access to this project", 1);
        }

        $this->insertUserProject->insert($userId, $hostId, $project);
    }
}
