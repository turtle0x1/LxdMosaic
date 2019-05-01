<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetHostsStorage
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
                "online"=>(bool) $host["Host_Online"],
                "pools"=>[]
            ];
            if ($host["Host_Online"] != true) {
                continue;
            }
            $client = $this->lxdClient->getANewClient($host["Host_ID"]);
            $pools = $client->storage->all();
            $withResources = [];
            foreach($pools as $pool){
                $withResources[] = [
                    "name"=>$pool,
                    "resources"=>$client->storage->resources->info($pool)
                ];
            }


            $details[$indent]["hostId"] = $host["Host_ID"];
            $details[$indent]["pools"] = $withResources;
        }
        return $details;
    }
}
