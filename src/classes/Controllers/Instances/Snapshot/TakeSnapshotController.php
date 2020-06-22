<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\TakeSnapshot;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class TakeSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(TakeSnapshot $takeSnapshot)
    {
        $this->takeSnapshot = $takeSnapshot;
    }
    /**
     * @Route("", name="Take Instance Snapshot")
     */
    public function takeSnapshot(Host $host, string $container, string $snapshotName)
    {
        $response = $this->takeSnapshot->takeSnapshot($host, $container, $snapshotName);
        return ["state"=>"success", "message"=>"Snapshot Started", "lxdResponse"=>$response];
    }
}
