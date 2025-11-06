<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Tools\Universe;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetHostProjectsOverview
{
    public function __construct(
        private readonly ValidatePermissions $validatePermissions,
        private readonly Universe $universe,
        private readonly FetchAllowedProjects $fetchAllowedProjects
    ) {
    }

    public function get(int $userId)
    {
        $this->validatePermissions->isAdminOrThrow($userId);

        $x = $this->universe->getEntitiesUserHasAccesTo($userId, 'projects');

        $y = $this->groupAllowedPermissions($this->fetchAllowedProjects->fetchAllUsersPermissions());

        foreach ($x['clusters'] as $cluster) {
            foreach ($cluster['members'] as &$host) {
                if (!$host->hostOnline()) {
                    continue;
                }
                $this->addHostDetails($host, $y);
            }
        }

        foreach ($x['standalone']['members'] as &$host) {
            if (!$host->hostOnline()) {
                continue;
            }
            $this->addHostDetails($host, $y);
        }

        return $x;
    }

    private function addHostDetails($host, $groupedPermissions)
    {
        $projects = $host->getCustomProp('projects');

        $out = [];

        foreach ($projects as $project) {
            $out[$project] = [
                'users' => [],
            ];
            if (isset($groupedPermissions[$host->getHostId()]) && isset($groupedPermissions[$host->getHostId()][$project])) {
                $out[$project]['users'] = $groupedPermissions[$host->getHostId()][$project];
            }
        }

        $host->setCustomProp('projects', $out);
    }

    private function groupAllowedPermissions($permissions)
    {
        $output = [];
        foreach ($permissions as $permission) {
            $hostId = $permission['hostId'];
            $project = $permission['project'];
            //
            if (!isset($output[$hostId])) {
                $output[$hostId] = [];
            }

            if (!isset($output[$hostId][$project])) {
                $output[$hostId][$project] = [];
            }

            $output[$hostId][$project][] = [
                'userId' => $permission['userId'],
                'userName' => $permission['userName'],
            ];
        }
        return $output;
    }
}
