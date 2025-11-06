<?php

namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;

class GetAllProfiles
{
    public function __construct(
        private readonly GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts
    ) {
    }

    /**
     * TODO This "agnostic" access (without a user id) needs to exist for
     *      deployments (should be updated) & metrics (metrics run wihtout user)
     */
    public function getAllProfiles(bool $profileRecursion = false)
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts->get();

        foreach ($clustersAndHosts['clusters'] as $clusterIndex => $cluster) {
            foreach ($cluster['members'] as $hostIndex => &$host) {
                $host->setCustomProp('profiles', $this->getProfiles($host, $profileRecursion));
            }
        }

        foreach ($clustersAndHosts['standalone']['members'] as $index => &$host) {
            $host->setCustomProp('profiles', $this->getProfiles($host, $profileRecursion));
        }

        return $clustersAndHosts;
    }

    private function getProfiles(Host $host, $profileRecursion)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        return $host->profiles->all($profileRecursion);
    }
}
