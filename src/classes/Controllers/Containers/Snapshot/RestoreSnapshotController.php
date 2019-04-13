<?php
namespace dhope0000\LXDClient\Controllers\Containers\Snapshot;

use dhope0000\LXDClient\Tools\Containers\Snapshot\RestoreSnapshot;

class RestoreSnapshotController
{
    public function __construct(RestoreSnapshot $restoreSnapshot)
    {
        $this->restoreSnapshot = $restoreSnapshot;
    }

    public function restoreSnapshot($host, $container, $snapshotName)
    {
        $this->restoreSnapshot->restoreSnapshot($host, $container, $snapshotName);
        return array("state"=>"success", "message"=>"Restored $snapshotName (snapshot) to $host - $container");
    }
}
