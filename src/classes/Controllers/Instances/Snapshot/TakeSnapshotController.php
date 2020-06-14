<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\TakeSnapshot;
use dhope0000\LXDClient\Objects\Host;

class TakeSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(TakeSnapshot $takeSnapshot)
    {
        $this->takeSnapshot = $takeSnapshot;
    }

    public function takeSnapshot(Host $host, string $container, string $snapshotName)
    {
        $response = $this->takeSnapshot->takeSnapshot($host, $container, $snapshotName);
        return ["state"=>"success", "message"=>"Snapshot Started", "lxdResponse"=>$response];
    }
}
