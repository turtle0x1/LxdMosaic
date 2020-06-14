<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Objects\Host;

class RenameProject
{
    public function rename(Host $host, string $project, string $newName)
    {
        return $host->projects->rename($project, $newName);
    }
}
