<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\DeleteProject;

class DeleteProjectController
{
    public function __construct(DeleteProject $deleteProject)
    {
        $this->deleteProject = $deleteProject;
    }

    public function delete(string $host, string $project)
    {
        $this->deleteProject->remove($host, $project);
        return ["state"=>"success", "message"=>"Deleted project"];
    }
}
