<?php

namespace dhope0000\LXDClient\Controllers\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\SoftwareAssets\GetSnapshotSoftwareList;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @Route("/api/Hosts/SoftwareAssets/GetSnapshotSoftwareListController/get", name="api_hosts_softwareassets_getsnapshotsoftwarelistcontroller_get", methods={"POST"})
     */
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
