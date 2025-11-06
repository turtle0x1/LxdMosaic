<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Projects\DeleteProject;
use Symfony\Component\Routing\Annotation\Route;

class DeleteProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteProject $deleteProject
    ) {
    }

    /**
     * @Route("/api/Projects/DeleteProjectController/delete", name="Delete Project", methods={"POST"})
     */
    public function delete(Host $host, string $project)
    {
        $this->deleteProject->remove($host, $project);
        return [
            'state' => 'success',
            'message' => 'Deleted project',
        ];
    }
}
