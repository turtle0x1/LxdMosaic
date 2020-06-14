<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Objects\Host;

class GetHostsStorage
{
    public function __construct(
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts
    ) {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
    }

    public function getAll()
    {
        $clusters = $this->getClustersAndStandaloneHosts->get();

        foreach ($clusters["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $host->setCustomProp("pools", $this->getHostPools($host));
            }
        }

        foreach ($clusters["standalone"]["members"] as $hostIndex => &$host) {
            $host->setCustomProp("pools", $this->getHostPools($host));
        }

        return $clusters;
    }

    private function getHostPools(Host $host)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        //TODO Recursion
        $pools = $host->storage->all();
        $withResources = [];
        foreach ($pools as $pool) {
            $withResources[] = [
                "name"=>$pool,
                "resources"=>$host->storage->resources->info($pool)
            ];
        }

        return $withResources;
    }
}
