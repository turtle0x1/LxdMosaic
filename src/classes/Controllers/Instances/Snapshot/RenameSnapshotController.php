<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\RenameSnapshot;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class RenameSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private RenameSnapshot $renameSnapshot;

    public function __construct(RenameSnapshot $renameSnapshot)
    {
        $this->renameSnapshot = $renameSnapshot;
    }
    /**
     * @Route("", name="Rename Instance Snapshot")
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
