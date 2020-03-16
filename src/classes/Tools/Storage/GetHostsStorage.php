<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;

class GetHostsStorage
{
    public function __construct(
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts,
        LxdClient $lxdClient
    ) {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
        $this->lxdClient = $lxdClient;
    }

    public function getAll()
    {
        $clusters = $this->getClustersAndStandaloneHosts->get();

        foreach ($clusters["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => $host) {
                $clusters["clusters"][$clusterIndex]["members"][$hostIndex]["pools"] = $this->getHostPools($host);
            }
        }

        foreach ($clusters["standalone"]["members"] as $hostIndex => $host) {
            $clusters["standalone"]["members"][$hostIndex]["pools"] = $this->getHostPools($host);
        }

        return $clusters;
    }

    private function getHostPools(array $host)
    {
        $indent = is_null($host["alias"]) ? $host["urlAndPort"] : $host["alias"];

        if (isset($host["hostOnline"]) && $host["hostOnline"] != true) {
            return [];
        }

        $client = $this->lxdClient->getANewClient($host["hostId"]);
        $pools = $client->storage->all();
        $withResources = [];
        foreach ($pools as $pool) {
            $withResources[] = [
                "name"=>$pool,
                "resources"=>$client->storage->resources->info($pool)
            ];
        }

        return $withResources;
    }
}
