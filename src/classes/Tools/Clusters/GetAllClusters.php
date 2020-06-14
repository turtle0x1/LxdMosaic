<?php

namespace dhope0000\LXDClient\Tools\Clusters;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Tools\Hosts\GetResources;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;

class GetAllClusters
{
    private $hostList;

    public function __construct(
        HostList $hostList,
        GetDetails $getDetails,
        GetResources $getResources
    ) {
        $this->hostList = $hostList;
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

        $hostsInACluster = [];

        $hosts = $this->hostList->getOnlineHostsWithDetails();

        foreach ($hosts as $host) {
            // I belive one host can only belong to one cluster so until that
            // isn't true then we can skip checking hosts we already know
            // are in a cluster some where
            if (in_array($host->getUrl(), $hostsInACluster)) {
                continue;
            }

            if (!$host->cluster->info()["enabled"]) {
                continue;
            }

            $clusterMembers = $host->cluster->members->all(LxdRecursionLevels::INSTANCE_FULL_RECURSION);

            foreach ($clusterMembers as $member) {
                $memberHostObj = $this->getDetails->fetchHostByUrl($member["url"]);

                $memberHostObj->setCustomProp("resources", $this->getResources->getHostExtended($memberHostObj));
                $memberHostObj->setCustomProp("status", $member["status"]);

                $clusters[$clusterId]["members"][] = $memberHostObj;
                $hostsInACluster[] = $member["url"];
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

            foreach ($cluster["members"] as &$member) {
                $resources = $member->getCustomProp("resources");

                $totalMemory += $resources["memory"]["total"];
                $usedMemory += $resources["memory"]["used"];

                if ($removeResources) {
                    $member->removeCustomProp("resources");
                }

                if ($member->getCustomProp("status") == "Online") {
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
