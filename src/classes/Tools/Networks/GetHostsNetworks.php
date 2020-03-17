<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;

class GetHostsNetworks
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
                $clusters["clusters"][$clusterIndex]["members"][$hostIndex]["networks"] = $this->getHostNetwork($host);
            }
        }

        foreach ($clusters["standalone"]["members"] as $hostIndex => $host) {
            $clusters["standalone"]["members"][$hostIndex]["networks"] = $this->getHostNetwork($host);
        }

        return $clusters;
    }

    private function getHostNetwork(array $host)
    {
        $indent = is_null($host["alias"]) ? $host["urlAndPort"] : $host["alias"];

        if (isset($host["hostOnline"]) && $host["hostOnline"] != true) {
            return [];
        }
        $client = $this->lxdClient->getANewClient($host["hostId"]);
        return $client->networks->all();
    }
}
