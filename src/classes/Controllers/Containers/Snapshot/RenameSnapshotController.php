<?php
namespace dhope0000\LXDClient\Controllers\Containers\Snapshot;

use dhope0000\LXDClient\Tools\Containers\Snapshot\RenameSnapshot;

class RenameSnapshotController
{
    public function __construct(RenameSnapshot $renameSnapshot)
    {
        $this->renameSnapshot = $renameSnapshot;
    }

    public function renameSnapshot(
        int $hostId,
        string $container,
        string $snapshotName,
        string $newSnapshotName,
        string $alias = null
    ) {
        $this->renameSnapshot->rename($hostId, $container, $snapshotName, $newSnapshotName);
        return array("state"=>"success", "message"=>"Renamed $snapshotName to $alias/$container/$newSnapshotName");
    }
}
