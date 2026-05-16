<?php

namespace dhope0000\LXDClient\Tools\Vulnerabilities\Scan;

use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Hosts\SoftwareAssets\FetchSoftwareAssetSnapshots;
use dhope0000\LXDClient\Model\Vulnerabilities\FetchVulnerabilitiesWithPackages;

/**
 * Cross-references the latest software assets snapshot against known vulns
 * to produce a rich overview of which instances/packages are at risk.
 */
class GetImpactedInstancesOverview
{
    public function __construct(
        private readonly FetchSoftwareAssetSnapshots $fetchSnapshot,
        private readonly FetchVulnerabilitiesWithPackages $fetchVulns,
        private readonly GetDetails $getDetails,
    ) {
    }

    /**
     * Returns an overview suitable for the vulnerability dashboard.
     */
    public function get(): array
    {
        $snapshot = $this->fetchSnapshot->fetchLatest();

        $output = [
            'has_snapshot' => false,
            'snapshot_date' => null,
            'total_instances_scanned' => 0,
            'impacted_instances_count' => 0,
            'total_impacted_packages' => 0,
            'instance_details' => [],
            'severity_breakdown' => [],
            'manager_breakdown' => [],
        ];

        if (empty($snapshot)) {
            return $output;
        }

        $output['has_snapshot'] = true;
        $output['snapshot_date'] = $snapshot['date'] ?? null;

        $data = json_decode((string) $snapshot['data'], true);
        if (empty($data)) {
            return $output;
        }

        $rows = $this->fetchVulns->fetchAll();
        $vulnPackageMap = $this->buildPackageLookup($rows);

        if (empty($vulnPackageMap)) {
            // No vulnerable packages known, so no impacts
            return $output;
        }

        $hostAliases = $this->getDetails->fetchAliases(array_keys($data));

        foreach ($data as $hostId => $projects) {
            foreach ($projects as $projectName => $instances) {
                foreach ($instances as $instanceName => $packages) {
                    $output['total_instances_scanned']++;

                    $impactedPackages = [];

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
                        $isImpacted = false;

                        foreach ($vulnPackageMap[$pkgKey] as $fixedVersion) {
                            if ($fixedVersion === null || $fixedVersion === '') {
                                $isImpacted = true;
                                break;
                            }

                            if ($installedVersion !== '' && version_compare($installedVersion, $fixedVersion, '<')) {
                                $isImpacted = true;
                                break;
                            }
                        }

                        if ($isImpacted) {
                            $impactedPackages[] = [
                                'name' => $pkg['name'] ?? '',
                                'installed_version' => $installedVersion,
                                'manager' => $manager,
                                'fixed_versions' => $vulnPackageMap[$pkgKey],
                            ];
                        }
                    }

                    if (!empty($impactedPackages)) {
                        $output['impacted_instances_count']++;
                        $output['total_impacted_packages'] += count($impactedPackages);

                        $output['instance_details'][] = [
                            'host_id' => $hostId,
                            'host_name' => $hostAliases[$hostId] ?? "Host {$hostId}",
                            'project' => $projectName,
                            'instance_name' => $instanceName,
                            'impacted_packages_count' => count($impactedPackages),
                            'impacted_packages' => $impactedPackages,
                        ];

                        // Manager breakdown
                        foreach ($impactedPackages as $pkg) {
                            $m = $pkg['manager'];
                            if (!isset($output['manager_breakdown'][$m])) {
                                $output['manager_breakdown'][$m] = [
                                    'manager' => $m,
                                    'count' => 0,
                                ];
                            }
                            $output['manager_breakdown'][$m]['count']++;
                        }
                    }
                }
            }
        }

        // Sort impacted instances by package count descending
        usort($output['instance_details'], function ($a, $b) {
            return $b['impacted_packages_count'] - $a['impacted_packages_count'];
        });

        // Convert manager breakdown to indexed array
        $output['manager_breakdown'] = array_values($output['manager_breakdown']);
        usort($output['manager_breakdown'], function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        return $output;
    }

    /**
     * Build a lookup map: lowercase package name => array of fixed versions from vuln DB.
     */
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
}
