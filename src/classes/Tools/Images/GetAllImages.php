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
            $indent = is_null($host["Host_Alias"]) ? $host["Host_Url_And_Port"] : $host["Host_Alias"];

            if ($host["Host_Online"] != true) {
                $output[$indent] = [
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

            $output[$indent] = [
                "online"=>true,
                "images"=>$details,
                "hostId"=>$host["Host_ID"]
            ];
        }
        return $output;
    }
}
