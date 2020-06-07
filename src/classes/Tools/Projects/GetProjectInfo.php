<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;

class GetProjectInfo
{
    public function get(Host $host, string $project)
    {
        $project = $host->projects->info($project);

        $project["used_by"] = StringTools::usedByStringsToLinks(
            $host->getHostId(),
            $project["used_by"],
            $host->getAlias(),
            null
        );
        return $project;
    }
}
