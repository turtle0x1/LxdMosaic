<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\RestoreSnapshot;
use dhope0000\LXDClient\Objects\Host;

use Symfony\Component\Routing\Annotation\Route;

class RestoreSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RestoreSnapshot $restoreSnapshot)
    {
        $this->restoreSnapshot = $restoreSnapshot;
    }
    /**
     * @Route("/api/Instances/Snapshot/RestoreSnapshotController/restoreSnapshot", methods={"POST"}, name="Restore Instance Snapshot", options={"rbac" = "instances.snapshots.restore"})
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
