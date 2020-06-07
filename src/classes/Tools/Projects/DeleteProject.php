<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Objects\Host;

class DeleteProject
{
    public function remove(Host $host, string $project)
    {
        return $host->projects->remove($project);
    }
}
