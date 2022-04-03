<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\RenameProject;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RenameProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameProject $renameProject)
    {
        $this->renameProject = $renameProject;
    }
    /**
     * @Route("/api/Projects/RenameProjectController/rename", methods={"POST"}, name="Rename Project")
     */
    public function rename(Host $host, string $project, string $newName)
    {
        return $this->renameProject->rename($host, $project, $newName);
    }
}
