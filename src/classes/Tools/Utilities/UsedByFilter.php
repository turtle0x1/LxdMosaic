<?php

namespace dhope0000\LXDClient\Tools\Utilities;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;

class UsedByFilter
{
    private $fetchAllowedProjects;
    private $fetchUserDetails;

    public function __construct(FetchAllowedProjects $fetchAllowedProjects, FetchUserDetails $fetchUserDetails)
    {
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->fetchUserDetails = $fetchUserDetails;
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
            $url = parse_url($entity);
            $entityProject = "default";
            if (isset($url["query"])) {
                parse_str($url["query"], $queryVariables);
                if (isset($queryVariables["project"])) {
                    $entityProject = $queryVariables["project"];
                }
            }
            if (!$isAdmin && (!isset($allowedProjects[$host->getHostid()]) || !in_array($entityProject, $allowedProjects[$host->getHostid()]))) {
                unset($usedBy[$index]);
                continue;
            }
        }

        return array_values($usedBy);
    }
}
