<?php

namespace dhope0000\LXDClient\Tools\Projects\Search;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetCommonToHostsProjects
{
    private $validatePermissions;

    public function __construct(ValidatePermissions $validatePermissions)
    {
        $this->validatePermissions = $validatePermissions;
    }

    public function get(int $userId, HostsCollection $hosts)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $projectsCount = [];
        $totalHosts = 0;
        foreach ($hosts as $host) {
            $totalHosts++;
            $projects = $host->projects->all();
            foreach ($projects as $project) {
                if (!isset($projectsCount[$project])) {
                    $projectsCount[$project] = 0;
                }
                $projectsCount[$project]++;
            }
        }
        $output = [];
        foreach ($projectsCount as $project => $count) {
            if ($count === $totalHosts) {
                $output[] = $project;
            }
        }
        return $output;
    }
}
