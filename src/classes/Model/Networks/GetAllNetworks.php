<?php
namespace dhope0000\LXDClient\Model\Networks;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetAllNetworks
{
    public function __construct(LxdClient $lxdClient, HostList $hostList)
    {
        $this->client = $lxdClient;
        $this->hostList = $hostList;
    }

    public function getAll()
    {
        $details = array();
        foreach ($this->hostList->getHostList() as $host) {
            $client = $this->client->getClientByUrl($host);
            $details[$host] = [
                "networks"=>$client->networks->all()
            ];
        }
        return $details;
    }
}
