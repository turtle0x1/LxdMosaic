<?php
namespace dhope0000\LXDClient\Tools\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;

class DeleteSnapshot
{
    public function deleteSnapshot(Host $host, string $instance, string $snapshotName)
    {
        return $host->instances->snapshots->remove($instance, $snapshotName);
    }
}
