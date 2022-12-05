<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Tools\Universe;
use dhope0000\LXDClient\Objects\Host;

class GetUserStorage
{
    private Universe $universe;

    public function __construct(
        Universe $universe
    ) {
        $this->universe = $universe;
    }

    public function getAll(int $userId) :array
    {
        $clusters = $this->universe->getEntitiesUserHasAccesTo($userId, "pools");

        $stats = [
            "storage"=>[
                "total"=>0,
                "used"=>0
            ],
            "inodes"=>[
                "total"=>0,
                "used"=>0
            ],
        ];

        foreach ($clusters["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $pools = $this->getHostPools($host);
                $stats = $this->calculateStats($stats, $pools);
                $host->setCustomProp("pools", $pools);
            }
        }

        foreach ($clusters["standalone"]["members"] as $host) {
            $pools = $this->getHostPools($host);
            $stats = $this->calculateStats($stats, $pools);
            $host->setCustomProp("pools", $pools);
        }

        return [
            "hostDetails"=>$clusters,
            "stats"=>$stats
        ];
    }

    private function calculateStats(array $stats, array $pools) :array
    {
        foreach ($pools as $pool) {
            $stats["storage"]["total"] += $pool["resources"]["space"]["total"];
            $stats["storage"]["used"] += $pool["resources"]["space"]["used"];

            $stats["inodes"]["total"] += $pool["resources"]["inodes"]["total"];
            $stats["inodes"]["used"] += $pool["resources"]["inodes"]["used"];
        }
        return $stats;
    }

    private function getHostPools(Host $host) :array
    {
        if (!$host->hostOnline()) {
            return [];
        }

        //TODO Recursion
        $pools = $host->storage->all();
        $withResources = [];
        foreach ($pools as $pool) {
            $withResources[] = [
                "name"=>$pool,
                "resources"=>$host->storage->resources->info($pool)
            ];
        }

        return $withResources;
    }
}
