<?php
namespace dhope0000\LXDClient\Controllers\Containers\Snapshot;

use dhope0000\LXDClient\Tools\Containers\Snapshot\TakeSnapshot;

class TakeSnapshotController
{
    public function __construct(TakeSnapshot $takeSnapshot)
    {
        $this->takeSnapshot = $takeSnapshot;
    }

    public function takeSnapshot(int $hostId, string $container, string $snapshotName)
    {
        $response = $this->takeSnapshot->takeSnapshot($hostId, $container, $snapshotName);
        return ["state"=>"success", "message"=>"Snapshot Started", "lxdResponse"=>$response];
    }
}
