<?php

namespace dhope0000\LXDClient\Tools;

use dhope0000\LXDClient\App\RouteApi;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Users\Projects\FetchUserProject;
use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Tools\User\GetUserProject;

class Universe
{
    private $entityConversion = [
        'instances' => '/1.0/instances/',
        'images' => '/1.0/images/',
        'profiles' => '/1.0/profiles/',
        'networks' => '/1.0/networks/',
        'projects' => '', // Special case because projecs are "global"
        'pools' => '/1.0/storage-pools/',
    ];

    public function __construct(
        private readonly FetchAllowedProjects $fetchAllowedProjects,
        private readonly HostList $hostList,
        private readonly GetAllClusters $getAllClusters,
        private readonly FetchUserProject $fetchUserProject,
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly GetUserProject $getUserProject,
        private readonly HasExtension $hasExtension,
        private readonly RouteApi $routeApi
    ) {
    }

    public function getEntitiesUserHasAccesTo(int $userId, ?string $entity = null)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);

        $projectsWithAccess = $this->fetchAllowedProjects->fetchAll($userId);

        if (empty($projectsWithAccess) && !$isAdmin) {
            throw new \Exception('User has no access & is attempting to browse', 1);
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
                $host->setCustomProp($entity, []);

                if ($host->hostOnline() == false) {
                    continue;
                }

                $supportsProjects = $this->hasExtension->checkWithHost($host, 'projects');

                $entities = [];

                $allowedProjects = [];

                if (isset($projectsWithAccess[$host->getHostId()])) {
                    $allowedProjects = $projectsWithAccess[$host->getHostId()];
                }

                if ($entity == 'projects') {
                    if ($isAdmin === true) {
                        if (!$supportsProjects) {
                            $entities = ['default'];
                        } else {
                            $entities = $host->projects->all();
                        }
                    } else {
                        $entities = $allowedProjects;
                    }
                    $host->setCustomProp($entity, $entities);
                    continue;
                }

                if (!isset($userCurrentProjects[$host->getHostId()])) {
                    $currentProject = $this->getUserProject->getForHost($userId, $host);
                } else {
                    $currentProject = $userCurrentProjects[$host->getHostId()];
                }

                $reqProject = $this->routeApi->getRequestedProject();

                if ($reqProject !== null) {
                    $currentProject = $reqProject;
                }

                $entities = [];

                if (!$supportsProjects) {
                    $project = [
                        'name' => 'default',
                        'oldHost' => true,
                        'used_by' => [],
                        'config' => [
                            'features.profiles' => 'false',
                        ],
                    ];
                } else {
                    $project = $host->projects->info($currentProject);
                }

                if (!in_array($project['name'], $allowedProjects) && $isAdmin !== true) {
                    continue;
                }

                if (isset($project['oldHost'])) {
                    $project['used_by'] = $this->buildOldUsedBy($host);
                    unset($project['oldHost']);
                }

                $getDefaultIfNotSupported = ['profiles', 'images', 'networks'];

                if (in_array(
                    $entity,
                    $getDefaultIfNotSupported
                ) && (!isset($project['config']["features.{$entity}"]) || $project['config']["features.{$entity}"] == 'false')) {
                    $oldProject = $host->getProject();
                    $host->setProject('default');
                    $x = $host->{$entity}->all();
                    $host->setProject($oldProject);
                    $entities = array_merge($entities, $x);
                    $host->setCustomProp($entity, $entities);
                    continue;
                }

                foreach ($project['used_by'] as $usedBy) {
                    if (str_contains((string) $usedBy, (string) $searchingFor)) {
                        $usedBy = str_replace("?project={$currentProject}", '', $usedBy);
                        $entities[] = str_replace($searchingFor, '', $usedBy);
                    }
                }

                $host->setCustomProp($entity, $entities);
            }
        }

        $clusters = $this->getAllClusters->convertHostsToClusters($hosts, false);

        $hostsInClusterGroups = [];

        foreach ($clusters as $cluster) {
            $hostsInClusterGroups = array_merge(
                $hostsInClusterGroups,
                array_map(fn ($member) => $member->getHostId(), $cluster['members'])
            );
        }

        foreach ($hosts as $i => $host) {
            if (in_array($host->getHostId(), $hostsInClusterGroups)) {
                if (gettype($hosts) == 'array') {
                    unset($hosts[$i]);
                } else {
                    $hosts->removeHostId($host->getHostId());
                }
            }
        }

        $hosts = gettype($hosts) == 'array' ? $hosts : $hosts->getAllHosts();

        return [
            'clusters' => $clusters,
            'standalone' => [
                'members' => $hosts,
            ],
        ];
    }

    private function buildOldUsedBy($host)
    {
        $instances = $this->prefixString($host->instances->all(), '/1.0/instances/');
        $images = $this->prefixString($host->images->all(), '/1.0/images/');
        $profiles = $this->prefixString($host->profiles->all(), '/1.0/profiles/');
        $networks = $this->prefixString($host->networks->all(), '/1.0/networks/');
        return array_merge($instances, $images, $profiles, $networks);
    }

    private function prefixString($objs, $string)
    {
        foreach ($objs as $i => $obj) {
            $objs[$i] = "{$string}{$obj}";
        }
        return $objs;
    }
}
