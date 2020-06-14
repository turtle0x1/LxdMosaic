<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Objects\Host;

class GetAllProfiles
{
    public function __construct(GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts)
    {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
    }

    private function getProfiles(Host $host, $profileRecursion)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        return $host->profiles->all($profileRecursion);
    }


    public function getAllProfiles(bool $profileRecursion = false)
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts->get(true);

        foreach ($clustersAndHosts["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $host->setCustomProp("profiles", $this->getProfiles($host, $profileRecursion));
            }
        }

        foreach ($clustersAndHosts["standalone"]["members"] as $index => &$host) {
            $host->setCustomProp("profiles", $this->getProfiles($host, $profileRecursion));
        }

        return $clustersAndHosts;
    }
}
