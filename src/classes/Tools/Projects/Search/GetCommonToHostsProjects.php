<?php

namespace dhope0000\LXDClient\Tools\Projects\Search;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetCommonToHostsProjects
{
    private ValidatePermissions $validatePermissions;
    private HasExtension $hasExtension;

    public function __construct(ValidatePermissions $validatePermissions, HasExtension $hasExtension)
    {
        $this->validatePermissions = $validatePermissions;
        $this->hasExtension = $hasExtension;
    }

    public function get(int $userId, HostsCollection $hosts) :array
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $projectsCount = [];
        $totalHosts = 0;
        foreach ($hosts as $host) {
            $totalHosts++;

            $supportsProjects = $this->hasExtension->checkWithHost($host, "projects");

            if ($supportsProjects) {
                $projects = $host->projects->all();
            } else {
                $projects = ["default"];
            }

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
