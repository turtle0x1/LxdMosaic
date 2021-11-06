<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class GetProjectInfo
{
    public function __construct(FetchAllowedProjects $fetchAllowedProjects)
    {
        $this->fetchAllowedProjects = $fetchAllowedProjects;
    }

    public function get(Host $host, string $projectName)
    {
        $project = $host->projects->info($projectName);
        $project["used_by"] = StringTools::usedByStringsToLinks(
            $host,
            $project["used_by"]
        );
        $project["users"] = $this->fetchAllowedProjects->fetchUsersCanAcessProject($host->getHostId(), $projectName);
        return $project;
    }
}
