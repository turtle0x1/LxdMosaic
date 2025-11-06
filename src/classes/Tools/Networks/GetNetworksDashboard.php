<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Constants\LxdRecursionLevels;
use dhope0000\LXDClient\Tools\Universe;

class GetNetworksDashboard
{
    public function __construct(
        private readonly Universe $universe
    ) {
    }

    public function get(int $userId): array
    {
        $clustersAndHosts = $this->universe->getEntitiesUserHasAccesTo($userId, 'projects');

        foreach ($clustersAndHosts['clusters'] as $clusterIndex => $cluster) {
            foreach ($cluster['members'] as $hostIndex => &$host) {
                $this->processHost($host);
            }
        }

        foreach ($clustersAndHosts['standalone']['members'] as &$host) {
            $this->processHost($host);
        }

        return $clustersAndHosts;
    }

    private function processHost($host): void
    {
        $instances = [];

        if ($host->hostOnline()) {
            $instances = $host->instances->all(LxdRecursionLevels::INSTANCE_FULL_RECURSION);
        }

        $d = $this->getNetworksAnalytics($instances);

        $host->setCustomProp('instances', $d['instances']);
        $host->setCustomProp('totals', $d['totals']);
        $host->setCustomProp('project', $host->getProject());
        $host->removeCustomProp('projects');
    }

    private function getNetworksAnalytics(array $instances): array
    {
        $output = [];

        $totals = [
            'bytes_received' => 0,
            'bytes_sent' => 0,
            'packets_received' => 0,
            'packets_sent' => 0,
            'errors_received' => 0,
            'errors_sent' => 0,
            'packets_dropped_outbound' => 0,
            'packets_dropped_inbound' => 0,
        ];

        foreach ($instances as $instance) {
            if (!isset($instance['state']['network'])) {
                continue;
            }
            $output[$instance['name']] = [];
            foreach ($instance['state']['network'] as $networkName => $network) {
                if ($networkName == 'lo') {
                    continue;
                }
                $output[$instance['name']][$networkName] = $network['counters'];
                foreach ($network['counters'] as $key => $total) {
                    if (isset($totals[$key])) {
                        $totals[$key] += $total;
                    }
                }
            }
        }
        return [
            'totals' => $totals,
            'instances' => $output,
        ];
    }
}
