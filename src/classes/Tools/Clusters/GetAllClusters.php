<?php

namespace dhope0000\LXDClient\Tools\Clusters;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Tools\Hosts\GetResources;

class GetAllClusters
{
    private $hostList;

    public function __construct(
        HostList $hostList,
        LxdClient $lxdClient,
        GetDetails $getDetails,
        GetResources $getResources
    ) {
        $this->hostList = $hostList;
        $this->lxdClient = $lxdClient;
        $this->getDetails = $getDetails;
        $this->getResources = $getResources;
    }

    public function get(bool $removeResources = true)
    {
        $clusters = $this->createClusterGroupsWithInfo();
        return $this->calculateClusterStats($clusters, $removeResources);
    }

    private function createClusterGroupsWithInfo()
    {
        $clusterId = 0;

        $clusters = [];

        $hostClusterRelationship = [];

        $hosts = $this->hostList->getOnlineHostsWithDetails();

        foreach ($hosts as $host) {
            // I belive one host can only belong to one cluster so until that
            // isn't true then we can skip checking hosts we already know
            // are in a cluster some where
            if (in_array($host["Host_Url_And_Port"], $hostClusterRelationship)) {
                continue;
            }

            $client = $this->lxdClient->getANewClient($host["Host_ID"], true, false);

            $clusterMembers = $client->cluster->members->all();

            foreach ($clusterMembers as $member) {
                $info = $client->cluster->members->info($member);

                $hostId =  $this->getDetails->getIdByUrlMatch($info["url"]);
                $memberClient = $this->lxdClient->getANewClient($hostId);

                $info["resources"] = $this->getResources->getHostExtended($hostId);
                $info["hostId"] = $hostId;

                $clusters[$clusterId]["members"][] = $info;
                $hostClusterRelationship[] = $info["url"];
            }
            $clusterId++;
        }

        return $clusters;
    }

    private function calculateClusterStats(array $clusters, bool $removeResources)
    {
        foreach ($clusters as $index => $cluster) {
            $totalMemory = 0;
            $usedMemory = 0;

            $onlineMembers = 0;

            foreach ($cluster["members"] as $memberIndex => $member) {
                $totalMemory += $member["resources"]["memory"]["total"];
                $usedMemory += $member["resources"]["memory"]["used"];

                if ($removeResources) {
                    unset($clusters[$index]["members"][$memberIndex]["resources"]);
                }

                if ($member["status"] == "Online") {
                    $onlineMembers++;
                }
            }

            $status = count($cluster["members"]) == $onlineMembers ? "Online" : "Degraded";

            $clusters[$index]["stats"] = [
                "totalMemory"=>$totalMemory,
                "usedMemory"=>$usedMemory,
                "status"=>$status
            ];
        }
        return $clusters;
    }
}
