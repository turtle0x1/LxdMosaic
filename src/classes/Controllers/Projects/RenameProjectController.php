<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\RenameProject;
use dhope0000\LXDClient\Objects\Host;

class RenameProjectController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameProject $renameProject)
    {
        $this->renameProject = $renameProject;
    }

    public function rename(Host $host, string $project, string $newName)
    {
        return $this->renameProject->rename($host, $project, $newName);
    }
}
