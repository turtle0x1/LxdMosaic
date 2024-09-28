<?php

namespace dhope0000\LXDClient\Tools\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Hosts\SoftwareAssets\FetchSoftwareAssetSnapshots;
use dhope0000\LXDClient\Model\Hosts\GetDetails;

class GetSnapshotSoftwareList
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

    public function get(\DateTimeImmutable $date)
    {
        $snapshot = $this->fetchSoftwareAssetSnapshots->fetchForDate($date);

        if (empty($snapshot)) {
            return [];
        }

        $snapshot = json_decode($snapshot["data"], true);

        $output = [];

        if (empty($snapshot)) {
            return $output;
        }

        $hostAliases = $this->getDetails->fetchAliases(array_keys($snapshot));

        foreach ($snapshot as $hostId => $projects) {
            foreach ($projects as $project => $instances) {
                foreach ($instances as $instance => $packages) {
                    foreach ($packages as $package) {
                        $output[] = array_merge([
                            "hostName" => $hostAliases[$hostId],
                            "project" => $project,
                            "instance" => $instance
                        ], $package);
                    }
                }
            }
        }
        return $output;
    }
}
