<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetAllImages
{
    public function __construct(LxdClient $lxdClient, HostList $hostList)
    {
        $this->client = $lxdClient;
        $this->hostList = $hostList;
    }

    public function getAllHostImages()
    {
        $output = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            if ($host["Host_Online"] != true) {
                $output[$host["Host_Alias"]] = [
                    "hostId"=>$host["Host_ID"],
                    "online"=>false,
                    "images"=>[]
                ];
                continue;
            }

            $client = $this->client->getANewClient($host["Host_ID"]);
            $ids = $client->images->all();
            $details = [];

            foreach ($ids as $fingerprint) {
                $details[] = $client->images->info($fingerprint);
            }

            $output[$host["Host_Alias"]] = [
                "online"=>true,
                "images"=>$details,
                "hostId"=>$host["Host_ID"]
            ];
        }
        return $output;
    }
}
