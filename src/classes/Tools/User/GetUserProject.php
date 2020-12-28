<?php

namespace dhope0000\LXDClient\Tools\User;

use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Tools\User\SetUserProject;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class GetUserProject
{
    public function __construct(
        FetchAllowedProjects $fetchAllowedProjects,
        FetchUserProject $fetchUserProject,
        SetUserProject $setUserProject,
        FetchUserDetails $fetchUserDetails
    ) {
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->fetchUserProject = $fetchUserProject;
        $this->setUserProject = $setUserProject;
        $this->fetchUserDetails = $fetchUserDetails;
    }

    public function getForHost(int $userId, $host) :string
    {
        $project = $this->fetchUserProject->fetch($userId, $host->getHostId());

        if (!empty($project)) {
            return $project;
        }
        
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        if ($isAdmin) {
            $this->setUserProject->set($userId, $host->getHostId(), "default");
            return "default";
        }

        $allowedProjects = $this->fetchAllowedProjects->fetchForHost($userId, $host->getHostId());

        if (empty($allowedProjects)) {
            throw new \Exception("Trying to access host with no allowed projects", 1);
        }

        if (in_array("default", $allowedProjects)) {
            $project = "default";
        } else {
            $project = $allowedProjects[0];
        }

        $this->setUserProject->set($userId, $host->getHostId(), $project);

        return $project;
    }
}
