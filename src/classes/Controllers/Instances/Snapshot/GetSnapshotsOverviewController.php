<?php

namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Snapshot\GetSnapshotsOverview;
use Symfony\Component\Routing\Attribute\Route;

class GetSnapshotsOverviewController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly GetSnapshotsOverview $getSnapshotsOverview
    ) {
    }

    #[Route(path: '/api/Instances/Snapshot/GetSnapshotsOverviewController/get', name: 'Get an overview of existing snapshots & configuration', methods: ['POST'])]
    public function get(Host $host, string $container)
    {
        return $this->getSnapshotsOverview->get($host, $container);
    }
}
