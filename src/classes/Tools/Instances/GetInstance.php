<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Constants\LxdApiExtensions;
use dhope0000\LXDClient\Model\Deployments\FetchDeployments;
use dhope0000\LXDClient\Model\InstanceSettings\GetSettings;
use dhope0000\LXDClient\Model\Metrics\FetchMetrics;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\GetAllInstanceProxies;

class GetInstance
{
    public function __construct(
        private readonly FetchDeployments $fetchDeployments,
        private readonly HasExtension $hasExtension,
        private readonly FetchMetrics $fetchMetrics,
        private readonly GetAllInstanceProxies $getAllInstanceProxies,
        private readonly GetSettings $getSettings
    ) {
    }

    public function get(Host $host, string $instance)
    {
        $details = $host->instances->info($instance);
        $state = $host->instances->state($instance);
        $snapshots = $host->instances->snapshots->all($instance);

        $hostId = $host->getHostId();
        $deploymentDetails = $this->fetchDeployments->byHostContainer($hostId, $instance);

        $totalMemory = $host->resources->info()['memory']['total'];
        $memorySource = 'Available On Host';

        $proxyDevices = $this->getAllInstanceProxies->get($details['expanded_devices']);

        if (isset($details['config']['limits.memory'])) {
            $memorySource = 'Instance Limit';
            $totalMemory = $details['config']['limits.memory'];
        }

        foreach ($details['expanded_devices'] as $name => &$device) {
            if ($device['type'] == 'disk' && isset($device['pool'])) {
                if (isset($device['size'])) {
                    $results = [
                        'space' => [
                            'total' => $device['size'],
                        ],
                    ];
                } else {
                    $results = $host->storage->resources->info($device['pool']);
                }

                $state['disk'][$name]['poolSize'] = $results['space']['total'];
            }
        }

        return [
            'details' => $details,
            'state' => $state,
            'mosaicExtensions' => $this->getMosaicExtensions($host, $instance),
            'snapshots' => $snapshots,
            'deploymentDetails' => $deploymentDetails,
            'project' => $host->callClientMethod('getProject'),
            'proxyDevices' => $proxyDevices,
            'totalMemory' => [
                'source' => $memorySource,
                'total' => $totalMemory,
            ],
        ];
    }

    private function getMosaicExtensions($host, $instance)
    {
        $output = [
            'timers' => false,
            'packages' => false,
            'audit' => false,
            'metrics' => $this->fetchMetrics->instanceHasMetrics($host->getHostId(), $host->getProject(), $instance),
            'backups' => $this->hasExtension->checkWithHost($host, LxdApiExtensions::CONTAINER_BACKUP),
        ];
        $allSettings = $this->getSettings->getAllSettingsWithLatestValues();
        foreach ($allSettings as $setting) {
            if ($setting['settingId'] == InstanceSettingsKeys::TIMERS_MONITOR) {
                $output['timers'] = (int) $setting['currentValue'] === 1;
            } elseif ($setting['settingId'] == InstanceSettingsKeys::SOFTWARE_INVENTORY_MONITOR) {
                $output['packages'] = (int) $setting['currentValue'] === 1;
            } elseif ($setting['settingId'] == InstanceSettingsKeys::RECORD_ACTIONS) {
                $output['packages'] = (int) $setting['currentValue'] === 1;
            }
        }
        return $output;
    }
}
