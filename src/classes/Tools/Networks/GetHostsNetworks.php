<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetHostsNetworks
{
    public function __construct(HostList $hostList, LxdClient $lxdClient)
    {
        $this->hostList = $hostList;
        $this->lxdClient = $lxdClient;
    }

    public function getAll()
    {
        $details = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            $indent = is_null($host["Host_Alias"]) ? $host["Host_Url_And_Port"] : $host["Host_Alias"];
            $details[$indent] = [
                "hostId"=>$host["Host_ID"],
                "online"=>(bool) $host["Host_Online"],
                "networks"=>[]
            ];
            if ($host["Host_Online"] != true) {
                continue;
            }
            $client = $this->lxdClient->getANewClient($host["Host_ID"]);
            $details[$indent]["networks"] = $client->networks->all();
        }
        return $details;
    }
}
