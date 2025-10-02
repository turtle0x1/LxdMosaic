<?php

namespace dhope0000\LXDClient\Controllers\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\SoftwareAssets\GetSnapshotSoftwareList;

class GetSnapshotSoftwareListController
{
    private $fetchUserDetails;
    private $getSnapshotSoftwareList;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        GetSnapshotSoftwareList $getSnapshotSoftwareList
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->getSnapshotSoftwareList = $getSnapshotSoftwareList;
    }

    public function get(int $userId, string $date)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        $date = new \DateTimeImmutable($date);
        return $this->getSnapshotSoftwareList->get($date);
    }
}
