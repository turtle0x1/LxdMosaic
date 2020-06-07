<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\RenameSnapshot;
use dhope0000\LXDClient\Objects\Host;

class RenameSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameSnapshot $renameSnapshot)
    {
        $this->renameSnapshot = $renameSnapshot;
    }

    public function renameSnapshot(
        Host $host,
        string $container,
        string $snapshotName,
        string $newSnapshotName,
        string $alias = null
    ) {
        $this->renameSnapshot->rename($host, $container, $snapshotName, $newSnapshotName);
        return array("state"=>"success", "message"=>"Renamed $snapshotName to $alias/$container/$newSnapshotName");
    }
}
