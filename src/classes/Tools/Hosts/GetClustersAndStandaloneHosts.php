<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Clusters\GetAllClusters;

class GetClustersAndStandaloneHosts
{
    public function __construct(
        private readonly GetAllClusters $getAllClusters,
        private readonly HostList $hostList
    ) {
    }

    public function get()
    {
        $clusters = $this->getAllClusters->get();

        $hostsInClusterGroups = [];

        foreach ($clusters as $cluster) {
            $hostsInClusterGroups = array_merge(
                $hostsInClusterGroups,
                array_map(fn ($member) => $member->getHostId(), $cluster['members'])
            );
        }

        if (empty($hostsInClusterGroups)) {
            $standaloneHosts = $this->hostList->fetchAllHosts();
        } else {
            $standaloneHosts = $this->hostList->fetchHostsNotInList($hostsInClusterGroups);
        }

        $standaloneHosts = [
            'members' => $standaloneHosts,
        ];

        return [
            'clusters' => $clusters,
            'standalone' => $standaloneHosts,
        ];
    }
}
