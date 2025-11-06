<?php

namespace dhope0000\LXDClient\Controllers\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Hosts\SoftwareAssets\FetchSoftwareAssetSnapshots;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Annotation\Route;

class GetSoftwareAsssetsHeadersController
{
    public function __construct(
        private readonly FetchUserDetails $fetchUserDetails,
        private readonly FetchSoftwareAssetSnapshots $fetchSoftwareAssetSnapshots
    ) {
    }

    /**
     * @Route("/api/Hosts/SoftwareAssets/GetSoftwareAsssetsHeadersController/get", name="api_hosts_softwareassets_getsoftwareasssetsheaderscontroller_get", methods={"POST"})
     */
    public function get(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        return $this->fetchSoftwareAssetSnapshots->fetchLastSevenHeaders();
    }
}
