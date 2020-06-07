<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Objects\Host;

class GetAllProfiles
{
    public function __construct(LxdClient $lxdClient, GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts)
    {
        $this->client = $lxdClient;
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
    }

    private function getProfiles(Host $host)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        return $host->profiles->all();
    }


    public function getAllProfiles()
    {
        $clustersAndHosts = $this->getClustersAndStandaloneHosts->get(true);

        foreach ($clustersAndHosts["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $host->setCustomProp("profiles", $this->getProfiles($host));
            }
        }

        foreach ($clustersAndHosts["standalone"]["members"] as $index => &$host) {
            $host->setCustomProp("profiles", $this->getProfiles($host));
        }

        return $clustersAndHosts;
    }

    public function getHostProfilesWithDetails(int $hostId)
    {
        $client = $this->client->getANewClient($hostId);
        $profiles = $client->profiles->all();
        return $this->getProfileDetails($client, $profiles);
    }

    public function getProfileDetails($client, $profiles)
    {
        $details = array();
        foreach ($profiles as $profile) {
            $state = $client->profiles->info($profile);
            $details[$profile] = ["details"=>$state];
        }
        return $details;
    }
}
