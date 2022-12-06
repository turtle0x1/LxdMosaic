<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\DeleteProject;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private DeleteProject $deleteProject;

    public function __construct(DeleteProject $deleteProject)
    {
        $this->deleteProject = $deleteProject;
    }
    /**
     * @Route("", name="Delete Project")
     */
    public function delete(Host $host, string $project)
    {
        $this->deleteProject->remove($host, $project);
        return ["state"=>"success", "message"=>"Deleted project"];
    }
}
