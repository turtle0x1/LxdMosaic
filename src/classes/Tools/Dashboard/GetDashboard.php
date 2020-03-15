<?php

namespace dhope0000\LXDClient\Tools\Dashboard;

use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Client\LxdClient;

class GetDashboard
{
    public function __construct(
        GetAllClusters $getAllClusters,
        HostList $hostList,
        LxdClient $lxdClient
    ) {
        $this->getAllClusters = $getAllClusters;
        $this->hostList = $hostList;
        $this->lxdClient = $lxdClient;
    }

    public function get()
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts();

        return [
            "clustersAndHosts"=>$clustersAndHosts
        ];
    }

    private function getClustersAndStandaloneHosts()
    {
        $clusters = $this->getAllClusters->get(false);

        $hostsInClusterGroups = [];

        foreach ($clusters as $cluster) {
            $hostsInClusterGroups = array_merge($hostsInClusterGroups, array_column($cluster["members"], "hostId"));
        }

        $standaloneHosts = $this->hostList->fetchHostsNotInList($hostsInClusterGroups);

        foreach ($standaloneHosts as $index => $host) {
            $standaloneHosts[$index]["resources"] = [];

            if ((int)$host["Host_Online"] === 0) {
                continue;
            }
            
            $client = $this->lxdClient->getANewClient($host["Host_ID"]);
            $info = $client->resources->info();
            $standaloneHosts[$index]["resources"] = $info;
        }

        return [
            "clusters"=>$clusters,
            "standalone"=>$standaloneHosts
        ];
    }
}
