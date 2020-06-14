<?php
namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;

class TakeSnapshot
{
    public function takeSnapshot(Host $host, string $instance, string $snapshotName)
    {
        return $host->instances->snapshots->create($instance, $snapshotName, false, true);
    }
}
