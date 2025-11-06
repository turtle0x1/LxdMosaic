<?php

namespace dhope0000\LXDClient\Controllers\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Tools\Hosts\SoftwareAssets\GetSoftwareSnapshotOverview;
use Symfony\Component\Routing\Attribute\Route;

class GetSoftwareAssetsOverviewController
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly GetSoftwareSnapshotOverview $getSoftwareSnapshotOverview
    ) {
    }

    #[Route(path: '/api/Hosts/SoftwareAssets/GetSoftwareAssetsOverviewController/get', name: 'api_hosts_softwareassets_getsoftwareassetsoverviewcontroller_get', methods: ['POST'])]
    public function get(int $userId, string $date)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        $date = new \DateTimeImmutable($date);
        return $this->getSoftwareSnapshotOverview->get($date);
    }
}
