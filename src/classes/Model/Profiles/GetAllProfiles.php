<?php
namespace dhope0000\LXDClient\Model\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetAllProfiles
{
    public function __construct(LxdClient $lxdClient, HostList $hostList)
    {
        $this->client = $lxdClient;
        $this->hostList = $hostList;
    }

    public function getAllProfiles()
    {
        $details = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            $client = $this->client->getANewClient($host["Host_ID"]);
            $indent = is_null($host["Host_Alias"]) ? $host["Host_Url_And_Port"] : $host["Host_Alias"];
            $profiles = $client->profiles->all();
            $details[$indent] = [
                "hostIp"=>$host["Host_Url_And_Port"],
                "profiles"=>$this->getProfileDetails($client, $profiles)
            ];
        }
        return $details;
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
