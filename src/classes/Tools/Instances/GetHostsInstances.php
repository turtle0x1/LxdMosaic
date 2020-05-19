<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;

class GetHostsInstances
{
    public function __construct(LxdClient $lxdClient, HostList $hostList, HasExtension $hasExtension)
    {
        $this->hostList = $hostList;
        $this->client = $lxdClient;
        $this->hasExtension = $hasExtension;
    }

    public function getAll($skipOffline = false)
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

            $instances = $this->getContainers($host["Host_ID"], $client);

            $supportsBackups = $this->hasExtension->checkWithClient($client, LxdApiExtensions::CONTAINER_BACKUP);

            $details[$host["Host_Alias"]] = [
                "online"=>true,
                "hostId"=>$host["Host_ID"],
                "containers"=>$instances,
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

        $recur = $this->hasExtension->checkWithClient($client, LxdApiExtensions::CONTAINER_FULL);

        $recur = $recur ? LxdRecursionLevels::INSTANCE_FULL_RECURSION : LxdRecursionLevels::INSTANCE_NO_RECURSION;

        $instances = $client->instances->all($recur);

        $instances = $this->addInstancesStateAndInfo($client, $instances);

        ksort($instances, SORT_STRING | SORT_FLAG_CASE);
        return $instances;
    }

    private function addInstancesStateAndInfo($client, $instances)
    {
        $hostInfo = $client->host->info();

        foreach ($instances as $index => $instance) {
            if (is_string($instance)) {
                $instance = $client->instances->info($instance);
                $instance["state"] = $client->instances->state($instance["name"]);
            } else {
                // Keep the return between get all and using LXD recusion method
                // the above is slow enough lets not force it to add +2 api
                // calls to match this array
                unset($instance["backups"]);
                unset($instance["snapshots"]);
            }

            unset($instances[$index]);

            if ($instance["location"] !== "") {
                if ($instance["location"] !== "none" && $instance["location"] !== $hostInfo["environment"]["server_name"]) {
                    continue;
                }
            }

            $instances[$instance["name"]] = $instance;
        }

        return $instances;
    }
}
