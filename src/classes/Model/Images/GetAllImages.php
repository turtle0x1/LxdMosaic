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
            $client = $this->client->getANewClient($host["Host_ID"]);
            $ids = $client->images->all();
            $details = [];
            $indent = is_null($host["Host_Alias"]) ? $host["Host_Url_And_Port"] : $host["Host_Alias"];
            foreach ($ids as $fingerprint) {
                $details[] = $client->images->info($fingerprint);
            }

            $output[$indent] = [
                "hostIp"=>$host["Host_Url_And_Port"],
                "images"=>$details
            ];
        }
        return $output;
    }
}
