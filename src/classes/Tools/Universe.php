<?php

namespace dhope0000\LXDClient\Tools;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;

class Universe
{
    private $entityConversion = [
        "instances"=>"/1.0/instances/",
        "images"=>"/1.0/images/",
        "profiles"=>"/1.0/profiles/",
        "networks"=>"/1.0/networks/",
        "projects"=>"", // Special case because projecs are "global"
        "volumes"=>"/1.0/storage-pools/"
    ];

    public function __construct(
        FetchAllowedProjects $fetchAllowedProjects,
        HostList $hostList,
        GetAllClusters  $getAllClusters,
        FetchUserProject $fetchUserProject
    ) {
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->hostList = $hostList;
        $this->getAllClusters = $getAllClusters;
        $this->fetchUserProject = $fetchUserProject;
    }

    public function getEntitiesUserHasAccesTo(int $userId, string $entity = null)
    {
        $projectsWithAccess = $this->fetchAllowedProjects->fetchAll($userId);
        $hosts = $this->hostList->getHostCollection(array_keys($projectsWithAccess));

        //TODO What happens when the user logs in and hasn't selected a project yet
        $userCurrentProjects = $this->fetchUserProject->fetchCurrentProjects($userId);

        if (!empty($entity)) {
            $searchingFor = $this->entityConversion[$entity];

            foreach ($hosts as $host) {
                $entities = [];

                $allowedProjects = $projectsWithAccess[$host->getHostId()];

                if ($entity == "projects") {
                    $entities = $allowedProjects;
                } else {
                    $currentProject = $userCurrentProjects[$host->getHostId()];
                    $entities = [];
                    $project = $host->projects->info($currentProject);

                    if (in_array($project["name"], $allowedProjects)) {
                        foreach ($project["used_by"] as $usedBy) {
                            if (strpos($usedBy, $searchingFor) !== false) {
                                $usedBy = str_replace("?project=$currentProject", "", $usedBy);
                                $entities[] = str_replace($searchingFor, "", $usedBy);
                            }
                        }
                    }
                }
                $host->setCustomProp($entity, $entities);
            }
        }

        $clusters = $this->getAllClusters->convertHostsToClusters($hosts);

        $hostsInClusterGroups = [];

        foreach ($clusters as $cluster) {
            $hostsInClusterGroups = array_merge($hostsInClusterGroups, array_map(function ($member) {
                return $member->getHostId();
            }, $cluster["members"]));
        }

        foreach ($hosts as $i => $host) {
            if (in_array($host->getHostId(), $hostsInClusterGroups)) {
                unset($hosts[$i]);
            }
        }

        return [
            "clusters"=>$clusters,
            "standalone"=>["members"=>$hosts]
        ];
    }
}
