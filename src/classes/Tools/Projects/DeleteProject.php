<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\Projects\InsertUserProject;

class DeleteProject
{
    public function __construct(InsertUserProject $insertUserProject)
    {
        $this->insertUserProject = $insertUserProject;
    }

    public function remove(Host $host, string $project)
    {
        $hostProjects = $host->projects->all();

        $this->insertUserProject->putUsersOnProjectToProject($host->getHostId(), $project, "default");

        return $host->projects->remove($project);
    }
}
