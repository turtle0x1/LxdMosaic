<?php

namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

use dhope0000\LXDClient\Constants\LxdRecursionLevels;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class GetSnapshotsOverview
{
    public function __construct(
        private readonly HasExtension $hasExtension
    ) {
    }

    public function get(Host $host, string $instance)
    {
        $schedule = null;
        if ($this->hasExtension->checkWithHost($host, 'snapshot_scheduling')) {
            $schedule = [
                'snapshots.expiry' => null,
                'snapshots.pattern' => null,
                'snapshots.schedule' => null,
                'snapshots.schedule.stopped' => null,
            ];
            $y = $host->instances->info($instance);
            foreach ($schedule as $key => &$value) {
                if (isset($y['config'][$key])) {
                    $value = $y['config'][$key];
                }
            }
        }
        return [
            'snapshots' => $host->instances->snapshots->all($instance, LxdRecursionLevels::INSTANCE_FULL_RECURSION),
            'schedule' => $schedule,
        ];
    }
}
