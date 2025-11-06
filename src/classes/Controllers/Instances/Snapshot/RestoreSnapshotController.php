<?php

namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Snapshot\RestoreSnapshot;
use Symfony\Component\Routing\Annotation\Route;

class RestoreSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly RestoreSnapshot $restoreSnapshot
    ) {
    }

    /**
     * @Route("/api/Instances/Snapshot/RestoreSnapshotController/restoreSnapshot", name="Restore Instance Snapshot", methods={"POST"})
     */
    public function restoreSnapshot(Host $host, string $container, string $snapshotName)
    {
        $this->restoreSnapshot->restoreSnapshot($host, $container, $snapshotName);
        return [
            'state' => 'success',
            'message' => "Restored {$snapshotName} (snapshot) to {$host->getAlias()} - {$container}",
        ];
    }
}
