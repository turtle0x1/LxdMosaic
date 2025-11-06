<?php

namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Snapshot\TakeSnapshot;
use Symfony\Component\Routing\Attribute\Route;

class TakeSnapshotController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly TakeSnapshot $takeSnapshot
    ) {
    }

    #[Route(path: '/api/Instances/Snapshot/TakeSnapshotController/takeSnapshot', name: 'Take Instance Snapshot', methods: ['POST'])]
    public function takeSnapshot(Host $host, string $container, string $snapshotName)
    {
        $response = $this->takeSnapshot->takeSnapshot($host, $container, $snapshotName);
        return [
            'state' => 'success',
            'message' => 'Snapshot Started',
            'lxdResponse' => $response,
        ];
    }
}
