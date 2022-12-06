<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\RenameProject;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RenameProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private RenameProject $renameProject;

    public function __construct(RenameProject $renameProject)
    {
        $this->renameProject = $renameProject;
    }
    /**
     * @Route("", name="Rename Project")
     */
    public function rename(Host $host, string $project, string $newName)
    {
        return $this->renameProject->rename($host, $project, $newName);
    }
}
