<?php

namespace dhope0000\LXDClient\Tools\Utilities;

use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Objects\Host;

class UsedByFilter
{
    public function __construct(
        private readonly FetchAllowedProjects $fetchAllowedProjects,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    public function filterUserProjects(int $userId, Host $host, array $usedBy)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        $allowedProjects = [];

        if (!$isAdmin) {
            $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);
        }

        $projectEntities = [];

        foreach ($usedBy as $index => $entity) {
            $url = parse_url((string) $entity);
            $entityProject = 'default';
            if (isset($url['query'])) {
                parse_str($url['query'], $queryVariables);
                if (isset($queryVariables['project'])) {
                    $entityProject = $queryVariables['project'];
                }
            }
            if (!$isAdmin && (!isset($allowedProjects[$host->getHostid()]) || !in_array(
                $entityProject,
                $allowedProjects[$host->getHostid()]
            ))) {
                unset($usedBy[$index]);
                continue;
            }
        }

        return array_values($usedBy);
    }
}
