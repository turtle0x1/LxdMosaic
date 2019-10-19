<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\DeleteProject;

class DeleteProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteProject $deleteProject)
    {
        $this->deleteProject = $deleteProject;
    }

    public function delete(int $hostId, string $project)
    {
        $this->deleteProject->remove($hostId, $project);
        return ["state"=>"success", "message"=>"Deleted project"];
    }
}
