<?php

namespace dhope0000\LXDClient\Controllers\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\SoftwareAssets\GetSoftwareSnapshotOverview;

class GetSoftwareAssetsOverviewController
{
    private $fetchUserDetails;
    private $getSoftwareSnapshotOverview;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        GetSoftwareSnapshotOverview $getSoftwareSnapshotOverview
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->getSoftwareSnapshotOverview = $getSoftwareSnapshotOverview;
    }

    public function get(int $userId, string $date = null)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === '1';
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        if ($date !== null) {
            $date = new \DateTimeImmutable($date);
        }
        return $this->getSoftwareSnapshotOverview->get($date);
    }
}
