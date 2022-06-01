<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;
use dhope0000\LXDClient\Model\Users\Projects\DeleteUserProject;

class DeleteProject
{
    private $deleteUserAccess;
    private $deleteUserProject;
    
    public function __construct(
        DeleteUserAccess $deleteUserAccess,
        DeleteUserProject $deleteUserProject
    ) {
        $this->deleteUserAccess = $deleteUserAccess;
        $this->deleteUserProject = $deleteUserProject;
    }

    public function remove(Host $host, string $project)
    {
        $this->deleteUserAccess->deletAllForProject($host->getHostId(), $project);

        $this->deleteUserProject->removeAllUsersFromProject($host->getHostId(), $project);

        return $host->projects->remove($project);
    }
}
