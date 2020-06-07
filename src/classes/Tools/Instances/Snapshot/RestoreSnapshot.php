<?php
namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;

class RestoreSnapshot
{
    public function restoreSnapshot(Host $host, string $instance, string $snapshotName)
    {
        return $host->instances->snapshots->restore($instance, $snapshotName, true);
    }
}
