<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;

class GetProjectInfo
{
    public function get(Host $host, string $projectName)
    {
        $project = $host->projects->info($projectName);
        $host->setProject($projectName);
        $project["used_by"] = StringTools::usedByStringsToLinks(
            $host,
            $project["used_by"]
        );
        return $project;
    }
}
