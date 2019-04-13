<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;

class GetHostsContainers
{
    public function __construct(LxdClient $lxdClient, HostList $hostList)
    {
        $this->hostList = $hostList;
        $this->client = $lxdClient;
    }

    public function getHostsContainers()
    {
        $details = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            $indent = is_null($host["Host_Alias"]) ? $host["Host_Url_And_Port"] : $host["Host_Alias"];

            if ($host["Host_Online"] != true) {
                $details[$indent] = [
                    "online"=>false,
                    "hostId"=>$host["Host_ID"],
                    "containers"=>[]
                ];
                continue;
            }

            $client = $this->client->getANewClient($host["Host_ID"]);
            $containers = $client->containers->all();
            $details[$indent] = [
                "online"=>true,
                "hostId"=>$host["Host_ID"],
                "containers"=>$this->getContainersState($client, $containers)
            ];
        }
        return $details;
    }

    public function getContainersState($client, $containers)
    {
        $details = array();
        foreach ($containers as $container) {
            $state = $client->containers->state($container);
            $details[$container] = [
                "state"=>$state
            ];
        }
        return $details;
    }
}
