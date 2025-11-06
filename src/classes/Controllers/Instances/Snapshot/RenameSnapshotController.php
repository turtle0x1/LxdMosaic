<?php

namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Snapshot\RenameSnapshot;
use Symfony\Component\Routing\Annotation\Route;

class RenameSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly RenameSnapshot $renameSnapshot
    ) {
    }

    /**
     * @Route("/api/Instances/Snapshot/RenameSnapshotController/renameSnapshot", name="Rename Instance Snapshot", methods={"POST"})
     */
    public function renameSnapshot(Host $host, string $container, string $snapshotName, string $newSnapshotName)
    {
        $this->renameSnapshot->rename($host, $container, $snapshotName, $newSnapshotName);
        return [
            'state' => 'success',
            'message' => "Renamed {$snapshotName} to {$host->getAlias()}/{$container}/{$newSnapshotName}",
        ];
    }
}
