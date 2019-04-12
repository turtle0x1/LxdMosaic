<?php
namespace dhope0000\LXDClient\Model\Images;

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
                "hostIp"=>$host["Host_Url_And_Port"],
                "images"=>$details
            ];
        }
        return $output;
    }
}
