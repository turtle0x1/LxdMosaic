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
        foreach ($this->hostList->getHostList() as $host) {
            $client = $this->client->getClientByUrl($host);
            $ids = $client->images->all();
            $details = [];
            foreach ($ids as $fingerprint) {
                $details[] = $client->images->info($fingerprint);
            }

            $output[$host] = $details;
        }
        return $output;
    }
}
