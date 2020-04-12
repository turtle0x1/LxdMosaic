<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\RestoreSnapshot;

class RestoreSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RestoreSnapshot $restoreSnapshot)
    {
        $this->restoreSnapshot = $restoreSnapshot;
    }

    public function restoreSnapshot(
        int $hostId,
        string $container,
        string $snapshotName,
        string $alias = null
    ) {
        $this->restoreSnapshot->restoreSnapshot($hostId, $container, $snapshotName);
        return array("state"=>"success", "message"=>"Restored $snapshotName (snapshot) to $alias - $container");
    }
}
