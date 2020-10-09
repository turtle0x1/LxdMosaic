<?php

namespace dhope0000\LXDClient\Tools;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Hosts\HostList;

class Universe
{
    private $entityConversion = [
        "instances"=>"/1.0/instances",
        "images"=>"/1.0/images",
        "profiles"=>"/1.0/profiles",
    ];

    public function __construct(FetchAllowedProjects $fetchAllowedProjects, HostList $hostList)
    {
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->hostList = $hostList;
    }

    public function getEntitiesUserHasAccesTo(int $userId, string $entity)
    {
        $projectsWithAccess = $this->fetchAllowedProjects->fetchAll($userId);
        $hosts = $this->hostList->getHostCollection(array_keys($projectsWithAccess));

        $searchingFor = $this->entityConversion[$entity];

        foreach ($hosts as $host) {
            $hostProjects = $host->projects->all(2);
            $allowedProjects = $projectsWithAccess[$host->getHostId()];

            $entities = [];

            foreach ($hostProjects as $project) {
                if (in_array($project["name"], $allowedProjects)) {
                    if (!isset($entities[$project["name"]])) {
                        $entities[$project["name"]] = [];
                    }

                    foreach ($project["used_by"] as $usedBy) {
                        if (strpos($usedBy, $searchingFor) !== false) {
                            $entities[$project["name"]][] = $usedBy;
                        }
                    }
                }
            }
            $host->setCustomProp($entity, $entities);
        }
        return $hosts;
    }
}
