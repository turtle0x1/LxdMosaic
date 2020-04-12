<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\TakeSnapshot;

class TakeSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
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
