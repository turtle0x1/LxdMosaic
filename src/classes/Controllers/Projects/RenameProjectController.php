<?php

namespace dhope0000\LXDClient\Controllers\Projects;

use dhope0000\LXDClient\Tools\Projects\RenameProject;

class RenameProjectController
{
    public function __construct(RenameProject $renameProject)
    {
        $this->renameProject = $renameProject;
    }

    public function rename(string $host, string $project, string $newName){
        return $this->renameProject->rename($host, $project, $newName);
    }
}
