<?php
namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;

class RenameSnapshot
{
    public function rename(
        Host $host,
        string $instance,
        string $snapshotName,
        string $newSnapshotName
    ) {
        return $host->instances->snapshots->rename(
            $instance,
            $snapshotName,
            $newSnapshotName,
            true
        );
    }
}
