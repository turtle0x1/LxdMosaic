<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\GetSnapshotsOverview;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetSnapshotsOverviewController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $getSnapshotsOverview;

    public function __construct(GetSnapshotsOverview $getSnapshotsOverview)
    {
        $this->getSnapshotsOverview = $getSnapshotsOverview;
    }
    /**
     * @Route("", name="Get an overview of existing snapshots & configuration")
     */
    public function get(Host $host, string $container)
    {
        return $this->getSnapshotsOverview->get($host, $container);
    }
}
