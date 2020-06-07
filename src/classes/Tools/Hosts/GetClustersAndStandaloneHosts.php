<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\GetResources;

class GetClustersAndStandaloneHosts
{
    public function __construct(
        GetAllClusters $getAllClusters,
        HostList $hostList,
        GetResources  $getResources
    ) {
        $this->getAllClusters = $getAllClusters;
        $this->hostList = $hostList;
        $this->getResources = $getResources;
    }
    //TODO $removeResources should default to true but quicker at time of wrting
    public function get($removeResources = false)
    {
        $clusters = $this->getAllClusters->get($removeResources);

        $hostsInClusterGroups = [];

        foreach ($clusters as $cluster) {
            $hostsInClusterGroups = array_merge($hostsInClusterGroups, array_map(function ($member) {
                return $member->getHostId();
            }, $cluster["members"]));
        }

        if (empty($hostsInClusterGroups)) {
            $standaloneHosts = $this->hostList->fetchAllHosts();
        } else {
            $standaloneHosts = $this->hostList->fetchHostsNotInList($hostsInClusterGroups);
        }


        foreach ($standaloneHosts as $index => $host) {
            $standaloneHosts[$index]->setCustomProp("resources", []);

            if (!$host->hostOnline()) {
                continue;
            }

            $info = $this->getResources->getHostExtended($host->getHostId());
            $standaloneHosts[$index]->setCustomProp("resources", $info);
        }

        $standaloneHosts = [
            "members"=>$standaloneHosts
        ];

        return [
            "clusters"=>$clusters,
            "standalone"=>$standaloneHosts
        ];
    }
}
