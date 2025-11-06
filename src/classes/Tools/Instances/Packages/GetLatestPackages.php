<?php

namespace dhope0000\LXDClient\Tools\Instances\Packages;

use dhope0000\LXDClient\Model\Hosts\SoftwareAssets\FetchSoftwareAssetSnapshots;
use dhope0000\LXDClient\Objects\Host;

class GetLatestPackages
{
    public function __construct(
        private readonly FetchSoftwareAssetSnapshots $fetchSoftwareAssetSnapshots
    ) {
    }

    public function get(Host $host, string $instance)
    {
        $snapshot = $this->fetchSoftwareAssetSnapshots->fetchLatest();
        $packages = [];

        $data = json_decode((string) $snapshot['data'], true);

        if (isset($data[$host->getHostId()])) {
            $packages = $data[$host->getHostId()][$host->getProject()][$instance] ?? [];
        }
        $managerStats = [];

        foreach ($packages as $package) {
            if (!isset($managerStats[$package['manager']])) {
                $managerStats[$package['manager']] = 0;
            }
            $managerStats[$package['manager']]++;
        }
        asort($managerStats);

        return [
            'date' => $snapshot['date'],
            'packages' => $packages,
            'stats' => [
                'managers' => $managerStats,
            ],
        ];

    }
}
