<?php
namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetResources
{
    public function __construct(LxdClient $lxdClient, HostList $hostList)
    {
        $this->client = $lxdClient;
        $this->hostList = $hostList;
    }

    public function getHostResources($host)
    {
        return $this->client->getClientByUrl($host)->resources->info();
    }

    public function getAllHostRecourses()
    {
        $output = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            $client = $this->client->getANewClient($host["Host_ID"]);
            $details = $client->resources->info();
            $output[$host["Host_Url_And_Port"]] = $details;
        }
        return $output;
    }
}
