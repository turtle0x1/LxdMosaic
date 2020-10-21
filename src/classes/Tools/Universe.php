<?php

namespace dhope0000\LXDClient\Tools;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\User\GetUserProject;

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
        FetchUserProject $fetchUserProject,
        FetchUserDetails $fetchUserDetails,
        GetUserProject $getUserProject
    ) {
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->hostList = $hostList;
        $this->getAllClusters = $getAllClusters;
        $this->fetchUserProject = $fetchUserProject;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->getUserProject = $getUserProject;
    }

    public function getEntitiesUserHasAccesTo(int $userId, string $entity = null)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === "1";

        $projectsWithAccess = $this->fetchAllowedProjects->fetchAll($userId);

        if ($isAdmin === true) {
            $hosts = $this->hostList->fetchAllHosts();
        } else {
            $hosts = $this->hostList->getHostCollection(array_keys($projectsWithAccess));
        }

        $userCurrentProjects = $this->fetchUserProject->fetchCurrentProjects($userId);

        if (!empty($entity)) {
            $searchingFor = $this->entityConversion[$entity];

            foreach ($hosts as $host) {
                if ($host->hostOnline() == false) {
                    $host->setCustomProp($entity, []);
                    continue;
                }

                $entities = [];

                $allowedProjects = [];

                if (isset($projectsWithAccess[$host->getHostId()])) {
                    $allowedProjects = $projectsWithAccess[$host->getHostId()];
                }

                if ($entity == "projects") {
                    if ($isAdmin === true) {
                        $entities = $host->projects->all();
                    } else {
                        $entities = $allowedProjects;
                    }
                } else {
                    if (!isset($userCurrentProjects[$host->getHostId()])) {
                        $currentProject = $this->getUserProject->getForHost($userId, $host);
                    } else {
                        $currentProject = $userCurrentProjects[$host->getHostId()];
                    }

                    $entities = [];
                    $project = $host->projects->info($currentProject);

                    if ((in_array($project["name"], $allowedProjects)) || $isAdmin === true) {
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
