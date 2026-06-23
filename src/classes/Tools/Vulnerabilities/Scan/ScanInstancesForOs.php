<?php

namespace dhope0000\LXDClient\Tools\Vulnerabilities\Scan;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

/**
 * Walks all hosts/projects/instances and determines which unique OS releases are active.
 */
class ScanInstancesForOs
{
    public function __construct(
        private readonly HostList $hostList,
        private readonly HasExtension $hasExtension,
    ) {
    }

    /**
     * Returns [['name' => 'ubuntu', 'version' => '22.04', 'codename' => 'jammy'], ...]
     */
    public function scan(): array
    {
        $hosts = $this->hostList->getOnlineHostsWithDetails();

        $seen = [];
        foreach ($hosts as $host) {
            $supportsProjects = $this->hasExtension->checkWithHost($host, 'projects');
            $allProjects = [['name' => 'default', 'config' => []]];

            if ($supportsProjects) {
                $allProjects = $host->projects->all(2);
            }

            foreach ($allProjects as $project) {
                if ($supportsProjects) {
                    $host->setProject($project['name']);
                }

                foreach ($host->instances->all(1) as $instance) {
                    $osInfo = $this->extractOs($instance);
                    // Only Ubuntu has Canonical OVAL feeds
                    if (strtolower($osInfo['name']) !== 'ubuntu' || $osInfo['codename'] === '') {
                        continue;
                    }

                    $key = strtolower("{$osInfo['name']}:{$osInfo['version']}");
                    if (!isset($seen[$key])) {
                        $seen[$key] = [
                            'name'     => strtolower($osInfo['name']),
                            'version'  => $osInfo['version'],
                            'codename' => $osInfo['codename'],
                        ];
                    }
                }
            }
        }

        return array_values($seen);
    }

    private function extractOs(array $instance): array
    {
        $result = ['name' => '', 'version' => '', 'codename' => ''];

        if (!empty($instance['properties']['os'])) {
            $result['name'] = $instance['properties']['os'];
        } elseif (!empty($instance['config']['image.os'])) {
            $result['name'] = $instance['config']['image.os'];
        }

        // Only support Ubuntu — Canonical OVAL feeds are Ubuntu-only
        if (strtolower($result['name']) !== 'ubuntu') {
            return $result;
        }

        $release = '';
        if (!empty($instance['properties']['release'])) {
            $release = $instance['properties']['release'];
        } elseif (!empty($instance['config']['image.release'])) {
            $release = $instance['config']['image.release'];
        }

        // Try to get codename from label e.g. "Ubuntu 22.04 LTS (jammy)"
        if (!empty($instance['properties']['label'])) {
            if (preg_match('/\(([^)]+)\)/', $instance['properties']['label'], $m)) {
                $result['codename'] = $m[1];
            }
        }

        // Determine if $release is a version ("22.04") or a codename ("jammy")
        if ($release !== '') {
            if (preg_match('/^\d+\.\d+$/', $release)) {
                $result['version'] = $release;
            } else {
                // It's a codename
                $result['codename'] = $release;
            }
        }

        // Fill in whichever is missing from the other
        $versionMap = ['focal' => '20.04', 'jammy' => '22.04', 'noble' => '24.04'];
        $codenameMap = ['20.04' => 'focal', '22.04' => 'jammy', '24.04' => 'noble'];

        if ($result['codename'] !== '' && $result['version'] === '') {
            $result['version'] = $versionMap[$result['codename']] ?? '';
        }
        if ($result['version'] !== '' && $result['codename'] === '') {
            $result['codename'] = $codenameMap[$result['version']] ?? '';
        }

        return $result;
    }
}
