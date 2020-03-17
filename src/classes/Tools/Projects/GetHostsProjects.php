<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;

class GetHostsProjects
{
    public function __construct(
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts,
        LxdClient $lxdClient,
        HasExtension $hasExtension
    ) {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
        $this->lxdClient = $lxdClient;
        $this->hasExtension = $hasExtension;
    }

    public function getAll()
    {
        $clusters = $this->getClustersAndStandaloneHosts->get();

        foreach ($clusters["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => $host) {
                $clusters["clusters"][$clusterIndex]["members"][$hostIndex]["projects"] = $this->getHostProjects($host);
            }
        }

        foreach ($clusters["standalone"]["members"] as $hostIndex => $host) {
            $clusters["standalone"]["members"][$hostIndex]["projects"] = $this->getHostProjects($host);
        }

        return $clusters;
    }


    private function getHostProjects($host)
    {
        if (isset($host["hostOnline"]) && $host["hostOnline"] != true) {
            return [];
        }

        $client = $this->lxdClient->getANewClient($host["hostId"]);

        if (!$this->hasExtension->checkWithClient($client, "projects")) {
            return [];
        }

        return $client->projects->all();
    }
}
