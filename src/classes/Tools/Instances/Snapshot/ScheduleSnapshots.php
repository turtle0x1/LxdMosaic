<?php
namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;

class ScheduleSnapshots
{
    private HasExtension $hasExtension;

    public function __construct(HasExtension $hasExtension)
    {
        $this->hasExtension = $hasExtension;
    }

    public function schedule(
        Host $host,
        string $instance,
        string $schedule,
        string $pattern,
        string $expiry,
        int $snapshotStopped
    ) :void {
        if (!$this->hasExtension->checkWithHost($host, "snapshot_scheduling")) {
            throw new \Exception("Host doesn't support scheduling", 1);
        }

        $config = $host->instances->info($instance);

        if (empty($config["devices"])) {
            unset($config["devices"]);
        }

        $config["config"]["snapshots.schedule"] = $schedule;
        $config["config"]["snapshots.pattern"] = $pattern;
        $config["config"]["snapshots.expiry"] = $expiry;
        $config["config"]["snapshots.schedule.stopped"] = $snapshotStopped == 1 ? "true" : "false";

        $host->instances->replace($instance, $config, true);
    }
}
