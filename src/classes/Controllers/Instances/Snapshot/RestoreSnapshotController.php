<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\RestoreSnapshot;
use dhope0000\LXDClient\Objects\Host;

use Symfony\Component\Routing\Annotation\Route;

class RestoreSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $restoreSnapshot;
    
    public function __construct(RestoreSnapshot $restoreSnapshot)
    {
        $this->restoreSnapshot = $restoreSnapshot;
    }
    /**
     * @Route("", name="Restore Instance Snapshot")
     */
    public function restoreSnapshot(
        Host $host,
        string $container,
        string $snapshotName
    ) {
        $this->restoreSnapshot->restoreSnapshot($host, $container, $snapshotName);
        return array("state"=>"success", "message"=>"Restored $snapshotName (snapshot) to {$host->getAlias()} - $container");
    }
}
