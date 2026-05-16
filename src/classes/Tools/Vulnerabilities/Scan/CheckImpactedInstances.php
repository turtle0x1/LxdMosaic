<?php

namespace dhope0000\LXDClient\Tools\Vulnerabilities\Scan;

use dhope0000\LXDClient\Model\Vulnerabilities\FetchVulnerabilitiesWithPackages;
use dhope0000\LXDClient\Model\Hosts\SoftwareAssets\FetchSoftwareAssetSnapshots;

/**
 * Cross-references the latest software assets snapshot against known vulns
 * to determine which instances have vulnerable packages installed.
 */
class CheckImpactedInstances
{
    public function __construct(
        private readonly FetchSoftwareAssetSnapshots $fetchSnapshot,
        private readonly FetchVulnerabilitiesWithPackages $fetchVulns,
    ) {
    }

    public function check(): array
    {
        $snapshot = $this->fetchSnapshot->fetchLatest();
        if (empty($snapshot)) {
            return ['impacted_count' => 0];
        }

        $data = json_decode((string) $snapshot['data'], true);
        if (empty($data)) {
            return ['impacted_count' => 0];
        }

        $rows = $this->fetchVulns->fetchAll();
        $vulnPackageMap = $this->buildPackageLookup($rows);

        if (empty($vulnPackageMap)) {
            return ['impacted_count' => 0];
        }

        return ['impacted_count' => $this->countImpacted($data, $vulnPackageMap)];
    }

    private function buildPackageLookup(array $rows): array
    {
        $map = [];
        foreach ($rows as $row) {
            if ($row['pkg_name'] === null || $row['pkg_name'] === '') {
                continue;
            }

            $key = strtolower($row['pkg_name']);
            if (!isset($map[$key])) {
                $map[$key] = [];
            }
            $map[$key][] = $row['fixed_version'];
        }

        return $map;
    }

    private function countImpacted(array $snapshotData, array $vulnPackageMap): int
    {
        $count = 0;

        foreach ($snapshotData as $hostId => $projects) {
            foreach ($projects as $projectName => $instances) {
                foreach ($instances as $instanceName => $packages) {
                    foreach ($packages as $pkg) {
                        $manager = strtolower($pkg['manager'] ?? '');
                        if ($manager !== 'apt') {
                            continue;
                        }

                        $pkgKey = strtolower($pkg['name'] ?? '');
                        if (!isset($vulnPackageMap[$pkgKey])) {
                            continue;
                        }

                        $installedVersion = $pkg['version'] ?? '';

                        foreach ($vulnPackageMap[$pkgKey] as $fixedVersion) {
                            if ($fixedVersion === null || $fixedVersion === '') {
                                $count++;
                                continue 2;
                            }

                            if ($installedVersion !== '' && version_compare($installedVersion, $fixedVersion, '<')) {
                                $count++;
                                continue 2;
                            }
                        }
                    }
                }
            }
        }

        return $count;
    }
}
