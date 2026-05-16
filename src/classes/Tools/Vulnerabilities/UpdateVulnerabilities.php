<?php

namespace dhope0000\LXDClient\Tools\Vulnerabilities;

use dhope0000\LXDClient\Tools\Vulnerabilities\Fetch\FetchUbuntuVulnerabilities;
use dhope0000\LXDClient\Tools\Vulnerabilities\Scan\CheckImpactedInstances;
use dhope0000\LXDClient\Tools\Vulnerabilities\Scan\ScanInstancesForOs;

/**
 * Top-level orchestrator: scan instances → get OS list → fetch OVAL data → check impact.
 */
class UpdateVulnerabilities
{
    public function __construct(
        private readonly ScanInstancesForOs $scanInstancesForOs,
        private readonly FetchUbuntuVulnerabilities $fetchUbuntuVulns,
        private readonly CheckImpactedInstances $checkImpacted,
    ) {
    }

    public function update(): array
    {
        // Discover active OSes from managed instances
        $activeOs = $this->scanInstancesForOs->scan();

        if (empty($activeOs)) {
            return ['error' => 'No active OSes found on managed instances.'];
        }

        // Download and store vulnerabilities
        $fetchResult = $this->fetchUbuntuVulns->update($activeOs);

        // Cross-reference against latest software snapshot to find impacted instances
        $impactResult = $this->checkImpacted->check();

        return array_merge($fetchResult, [
            'active_os_count'   => count($activeOs),
            'impacted_instances' => $impactResult['impacted_count'] ?? 0,
        ]);
    }
}
