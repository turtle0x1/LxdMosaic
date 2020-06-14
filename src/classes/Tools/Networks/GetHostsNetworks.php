<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Objects\Host;

class GetHostsNetworks
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
                $host->setCustomProp("networks", $this->getHostNetwork($host));
            }
        }

        foreach ($clusters["standalone"]["members"] as $hostIndex => &$host) {
            $host->setCustomProp("networks", $this->getHostNetwork($host));
        }

        return $clusters;
    }

    private function getHostNetwork(Host $host)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        return $host->networks->all(2);
    }
}
