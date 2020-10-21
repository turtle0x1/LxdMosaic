<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Tools\User\SetUserProject;

class GetUserProject
{
    public function __construct(
        FetchAllowedProjects $fetchAllowedProjects,
        FetchUserProject $fetchUserProject,
        SetUserProject $setUserProject
    ) {
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->fetchUserProject = $fetchUserProject;
        $this->setUserProject = $setUserProject;
    }

    public function getForHost(int $userId, $host) :string
    {
        $project = $this->fetchUserProject->fetch($userId, $host->getHostId());
        if (!empty($project)) {
            return $project;
        }

        $allowedProjects = $this->fetchAllowedProjects->fetchForHost($userId, $host->getHostId());

        if (in_array("default", $allowedProjects)) {
            $project = "default";
        } else {
            $project = $allowedProjects[0];
        }

        $this->setUserProject->set($userId, $host->getHostId(), $project);

        return $project;
    }
}
