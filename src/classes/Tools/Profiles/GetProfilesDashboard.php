<?php

namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Tools\Universe;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;

class GetProfilesDashboard
{
    private $universe;

    public function __construct(Universe $universe)
    {
        $this->universe = $universe;
    }

    public function get(int $userId) :array
    {
        $clustersAndHosts = $this->universe->getEntitiesUserHasAccesTo($userId, "projects");

        foreach ($clustersAndHosts["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $this->processHost($host);
            }
        }

        foreach ($clustersAndHosts["standalone"]["members"] as &$host) {
            $this->processHost($host);
        }

        return $clustersAndHosts;
    }

    private function processHost($host) :void
    {
        $profiles = [];

        if ($host->hostOnline()) {
            $profiles = $host->profiles->all(LxdRecursionLevels::INSTANCE_FULL_RECURSION);
        }

        $d = $this->getProfilesAnalytics($profiles);

        $host->setCustomProp("profiles", $d);
        $host->setCustomProp("project", $host->getProject());
        $host->removeCustomProp("projects");
    }

    private function getProfilesAnalytics(array $profiles) :array
    {
        $output = [];

        foreach ($profiles as $profile) {
            $instances = [];

            foreach ($profile["used_by"] as $usedBy) {
                if (strpos($usedBy, "/1.0/instances/") !== false) {
                    $instances[] = str_replace("/1.0/instances/", "", $usedBy);
                }
            }

            $deviceTypes = [
                "proxy"=>0,
                "usb"=>0,
                "disk"=>0,
                "tpm"=>0,
                "gpu"=>0
            ];

            foreach ($profile["devices"] as $device) {
                if (isset($device["type"]) && isset($deviceTypes[$device["type"]])) {
                    $deviceTypes[$device["type"]]++;
                }
            }

            $hasVendorData = isset($profile["config"]) && isset($profile["config"]["user.vendor-data"]);
            $hasUserData = isset($profile["config"]) && isset($profile["config"]["user.user-data"]);

            $output[$profile["name"]] = array_merge([
                "instances"=>$instances,
                "hasVendorData"=>$hasVendorData,
                "hasUserData"=>$hasUserData
            ], $deviceTypes);
        }

        uasort($output, function ($a, $b) {
            $aC = count($a["instances"]);
            $bC = count($b["instances"]);

            if ($aC == $bC) {
                return 0;
            }

            return $aC < $bC ? 1 : -1;
        });

        return $output;
    }
}
