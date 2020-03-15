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

    public function get()
    {
        $clusters = $this->getAllClusters->get(false);

        $hostsInClusterGroups = [];

        foreach ($clusters as $cluster) {
            $hostsInClusterGroups = array_merge($hostsInClusterGroups, array_column($cluster["members"], "hostId"));
        }

        $standaloneHosts = $this->hostList->fetchHostsNotInList($hostsInClusterGroups);

        foreach ($standaloneHosts as $index => $host) {
            $standaloneHosts[$index]["resources"] = [];

            if ((int)$host["hostOnline"] === 0) {
                continue;
            }

            $info = $this->getResources->getHostExtended($host["hostId"]);
            $standaloneHosts[$index]["resources"] = $info;
        }

        return [
            "clusters"=>$clusters,
            "standalone"=>$standaloneHosts
        ];
    }
}
