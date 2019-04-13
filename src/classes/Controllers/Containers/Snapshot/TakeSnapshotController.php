<?php
namespace dhope0000\LXDClient\Controllers\Containers\Snapshot;

use dhope0000\LXDClient\Tools\Containers\Snapshot\TakeSnapshot;

class TakeSnapshotController
{
    public function __construct(TakeSnapshot $takeSnapshot)
    {
        $this->takeSnapshot = $takeSnapshot;
    }

    public function takeSnapshot($host, $container, $snapshotName)
    {
        $response = $this->takeSnapshot->takeSnapshot($host, $container, $snapshotName);
        return ["state"=>"success", "message"=>"Snapshot Started", "lxdResponse"=>$response];
    }
}
