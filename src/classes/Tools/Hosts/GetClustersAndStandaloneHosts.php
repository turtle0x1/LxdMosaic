<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetClustersAndStandaloneHosts
{
    private $getAllClusters;
    private $hostList;
    
    public function __construct(
        GetAllClusters $getAllClusters,
        HostList $hostList
    ) {
        $this->getAllClusters = $getAllClusters;
        $this->hostList = $hostList;
    }

    public function get()
    {
        $clusters = $this->getAllClusters->get();

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

        $standaloneHosts = [
            "members"=>$standaloneHosts
        ];

        return [
            "clusters"=>$clusters,
            "standalone"=>$standaloneHosts
        ];
    }
}
