<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Users\AllowedProjects\DeleteUserAccess;
use dhope0000\LXDClient\Model\Users\Projects\DeleteUserProject;
use dhope0000\LXDClient\Objects\Host;

class DeleteProject
{
    public function __construct(
        private readonly DeleteUserAccess $deleteUserAccess,
        private readonly DeleteUserProject $deleteUserProject
    ) {
    }

    public function remove(Host $host, string $project)
    {
        $this->deleteUserAccess->deletAllForProject($host->getHostId(), $project);

        $this->deleteUserProject->removeAllUsersFromProject($host->getHostId(), $project);

        return $host->projects->remove($project);
    }
}
