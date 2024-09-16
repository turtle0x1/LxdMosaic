<?php

namespace dhope0000\LXDClient\Tools\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Hosts\SoftwareAssets\FetchSoftwareAssetSnapshots;
use dhope0000\LXDClient\Model\Hosts\GetDetails;

class GetSoftwareSnapshotOverview
{
    private $fetchSoftwareAssetSnapshots;
    private $getDetails;

    public function __construct(
        FetchSoftwareAssetSnapshots $fetchSoftwareAssetSnapshots,
        GetDetails $getDetails
    ) {
        $this->fetchSoftwareAssetSnapshots = $fetchSoftwareAssetSnapshots;
        $this->getDetails = $getDetails;
    }

    public function get(\DateTimeImmutable $date = null)
    {
        if ($date == null) {
            $date = new \DateTimeImmutable();
        }
        $snapshot = $this->fetchSoftwareAssetSnapshots->fetchForDate($date);
        $snapshotHeaders = $this->fetchSoftwareAssetSnapshots->fetchLastSevenHeaders();


        $output = [
            "date" => $date->format("Y-m-d"),
            "totalPackages" => 0,
            "managerMetrics" => [],
            "hostMetrics" => [],
            "projectMetrics" => [],
            "packages" => []
        ];

        if ($snapshot == false) {
            return $output;
        }

        $snapshot = json_decode($snapshot["data"], true);

        if (empty($snapshot)) {
            return $output;
        }

        $hostAliases = $this->getDetails->fetchAliases(array_keys($snapshot));

        foreach ($snapshot as $hostId => $projects) {
            foreach ($projects as $project => $instances) {
                foreach ($instances as $instance => $packages) {
                    foreach ($packages as $package) {

                        $output["totalPackages"]++;

                        $manager = $package["manager"];
                        if (!isset($output["managerMetrics"][$manager])) {
                            $output["managerMetrics"][$manager] = [
                                "name" => $manager,
                                "packages" => 0
                            ];
                        }
                        $output["managerMetrics"][$manager]["packages"]++;

                        if (!isset($output["hostMetrics"][$hostId])) {
                            $output["hostMetrics"][$hostId] = [
                                "name" => $hostAliases[$hostId],
                                "packages" => 0,
                            ];
                        }
                        $output["hostMetrics"][$hostId]["packages"]++;

                        if (!isset($output["projectMetrics"][$project])) {
                            $output["projectMetrics"][$project] = [
                                "name" => $project,
                                "packages" => 0,
                            ];
                        }
                        $output["projectMetrics"][$project]["packages"]++;

                        $packageKey = $package["name"];

                        if (!isset($output["packages"][$packageKey])) {
                            $output["packages"][$packageKey] = [
                                "name" => $package["name"],
                                "totalInstalls"=>0,
                                "versions" => []
                            ];
                        }

                        $output["packages"][$packageKey]["totalInstalls"]++;

                        if (!isset($output["packages"][$packageKey]["versions"][$package["version"]])) {
                            $output["packages"][$packageKey]["versions"][$package["version"]] = [
                                "version" => $package["version"],
                                "installs" => 0
                            ];
                        }
                        $output["packages"][$packageKey]["versions"][$package["version"]]["installs"]++;
                    }
                }
            }
        }
        $output["managerMetrics"] = array_values($output["managerMetrics"]);
        $output["hostMetrics"] = array_values($output["hostMetrics"]);
        $output["projectMetrics"] = array_values($output["projectMetrics"]);
        $output["packages"] = array_values($output["packages"]);

        usort($output["packages"], [$this, "sortInstalls"]);

        $output["packages"] = array_slice($output["packages"], 0 , 20);

        return $output;
    }

    private function sortInstalls($a, $b)
    {
        return $a["totalInstalls"] > $b["totalInstalls"] ? -1 : 1;
    }
}
