<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Utilities\StringTools;

class GetProjectInfo
{
    public function __construct(
        private readonly FetchAllowedProjects $fetchAllowedProjects
    ) {
    }

    public function get(Host $host, string $projectName)
    {
        $project = $host->projects->info($projectName);
        $project['used_by'] = StringTools::usedByStringsToLinks($host, $project['used_by']);
        $project['users'] = $this->fetchAllowedProjects->fetchUsersCanAcessProject($host->getHostId(), $projectName);
        return $project;
    }
}
