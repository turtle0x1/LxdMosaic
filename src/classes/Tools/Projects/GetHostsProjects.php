<?php

namespace dhope0000\LXDClient\Tools\Projects;

use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;

class GetHostsProjects
{
    public function __construct(
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts,
        HasExtension $hasExtension
    ) {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
        $this->hasExtension = $hasExtension;
    }

    public function getAll()
    {
        $clusters = $this->getClustersAndStandaloneHosts->get(true);

        foreach ($clusters["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $host->setCustomProp("projects", $this->getHostProjects($host));
            }
        }

        foreach ($clusters["standalone"]["members"] as $hostIndex => &$host) {
            $host->setCustomProp("projects", $this->getHostProjects($host));
        }

        return $clusters;
    }


    private function getHostProjects($host)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        if (!$this->hasExtension->hasWithHostId($host->getHostId(), "projects")) {
            return [];
        }

        return $host->projects->all();
    }
}
