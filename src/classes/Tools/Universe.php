<?php

namespace dhope0000\LXDClient\Tools;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\User\GetUserProject;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

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
        GetUserProject $getUserProject,
        HasExtension $hasExtension
    ) {
        $this->fetchAllowedProjects = $fetchAllowedProjects;
        $this->hostList = $hostList;
        $this->getAllClusters = $getAllClusters;
        $this->fetchUserProject = $fetchUserProject;
        $this->fetchUserDetails = $fetchUserDetails;
        $this->getUserProject = $getUserProject;
        $this->hasExtension = $hasExtension;
    }

    public function getEntitiesUserHasAccesTo(int $userId, string $entity = null)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === "1";

        $projectsWithAccess = $this->fetchAllowedProjects->fetchAll($userId);

        if (empty($projectsWithAccess) && !$isAdmin) {
            throw new \Exception("User has no access & is attempting to browse", 1);
        }

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

                $supportsProjects = $this->hasExtension->checkWithHost($host, "projects");

                $entities = [];

                $allowedProjects = [];

                if (isset($projectsWithAccess[$host->getHostId()])) {
                    $allowedProjects = $projectsWithAccess[$host->getHostId()];
                }

                if ($entity == "projects") {
                    if ($isAdmin === true) {
                        if (!$supportsProjects) {
                            $projects = ["default"];
                        } else {
                            $entities = $host->projects->all();
                        }
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

                    if (!$supportsProjects) {
                        $project = ["name"=>"default", "oldHost"=>true, "used_by"=>[]];
                    } else {
                        $project = $host->projects->info($currentProject);
                    }


                    if ((in_array($project["name"], $allowedProjects)) || $isAdmin === true) {
                        if (isset($project["oldHost"])) {
                            $project["used_by"] = $this->buildOldUsedBy($host);
                            unset($project["oldHost"]);
                        }
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

    private function buildOldUsedBy($host)
    {
        $instances = $this->prefixString($host->instances->all(), "/1.0/instances/");
        $images = $this->prefixString($host->images->all(), "/1.0/images/");
        $profiles = $this->prefixString($host->profiles->all(), "/1.0/profiles/");
        $networks = $this->prefixString($host->networks->all(), "/1.0/networks/");
        return array_merge($instances, $images, $profiles, $networks);
    }

    private function prefixString($objs, $string)
    {
        foreach ($objs as $i => $obj) {
            $objs[$i] = "$string$obj";
        }
        return $objs;
    }
}
