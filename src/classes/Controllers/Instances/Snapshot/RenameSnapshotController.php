<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\RenameSnapshot;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RenameSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameSnapshot $renameSnapshot)
    {
        $this->renameSnapshot = $renameSnapshot;
    }
    /**
     * @Route("/api/Instances/Snapshot/RenameSnapshotController/renameSnapshot", methods={"POST"}, name="Rename Instance Snapshot", options={"rbac" = "instances.snapshots.rename"})
     */
    public function renameSnapshot(
        Host $host,
        string $container,
        string $snapshotName,
        string $newSnapshotName
    ) {
        $this->renameSnapshot->rename($host, $container, $snapshotName, $newSnapshotName);
        return array("state"=>"success", "message"=>"Renamed $snapshotName to {$host->getAlias()}/$container/$newSnapshotName");
    }
}
