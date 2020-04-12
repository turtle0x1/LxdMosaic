<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\RenameSnapshot;

class RenameSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
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
