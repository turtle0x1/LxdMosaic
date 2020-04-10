<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;

class GetHostsContainers
{
    public function __construct(LxdClient $lxdClient, HostList $hostList, HasExtension $hasExtension)
    {
        $this->hostList = $hostList;
        $this->client = $lxdClient;
        $this->hasExtension = $hasExtension;
    }

    public function getHostsContainers($skipOffline = false)
    {
        $details = array();
        foreach ($this->hostList->getHostListWithDetails() as $host) {
            if (!$host["Host_Online"] && $skipOffline) {
                continue;
            }

            if ($host["Host_Online"] != true) {
                $details[$host["Host_Alias"]] = [
                    "online"=>false,
                    "hostId"=>$host["Host_ID"],
                    "containers"=>[],
                    "hostInfo"=>[],
                    "supportsBackups"=>false
                ];
                continue;
            }

            $client = $this->client->getANewClient($host["Host_ID"]);
            $hostInfo = $client->host->info();

            $containers = $this->getContainers($host["Host_ID"], $client);

            $supportsBackups = $this->hasExtension->checkWithClient($client, LxdApiExtensions::CONTAINER_BACKUP);

            $details[$host["Host_Alias"]] = [
                "online"=>true,
                "hostId"=>$host["Host_ID"],
                "containers"=>$containers,
                "hostInfo"=>$hostInfo,
                "supportsBackups"=>$supportsBackups
            ];
        }
        return $details;
    }

    public function getContainers(int $hostId, $client = null)
    {
        if (is_null($client)) {
            $client = $this->client->getANewClient($hostId);
        }

        $supportsVms = $this->hasExtension->checkWithClient($client, LxdApiExtensions::VIRTUAL_MACHINES);

        $containers = $client->instances->all();

        $containers = $this->addContainersStateAndInfo($client, $containers);
        ksort($containers, SORT_STRING | SORT_FLAG_CASE);
        return $containers;
    }

    private function addContainersStateAndInfo($client, $containers)
    {
        $hostInfo = $client->host->info();
        $details = array();
        foreach ($containers as $container) {
            $state = $client->instances->state($container);
            $info = $client->instances->info($container);
            
            if ($info["location"] !== "") {
                if ($info["location"] !== "none" && $info["location"] !== $hostInfo["environment"]["server_name"]) {
                    continue;
                }
            }

            $details[$container] = [
                "state"=>$state,
                "info"=>$info
            ];
        }
        return $details;
    }
}
