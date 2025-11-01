<?php

namespace dhope0000\LXDClient\Controllers\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Hosts\SoftwareAssets\FetchSoftwareAssetSnapshots;
use Symfony\Component\Routing\Annotation\Route;

class GetSoftwareAsssetsHeadersController
{
    private $fetchUserDetails;
    private $fetchSoftwareAssetSnapshots;

    public function __construct(
        FetchUserDetails $fetchUserDetails,
        FetchSoftwareAssetSnapshots $fetchSoftwareAssetSnapshots
    ) {
        $this->fetchUserDetails = $fetchUserDetails;
        $this->fetchSoftwareAssetSnapshots = $fetchSoftwareAssetSnapshots;
    }

    /**
     * @Route("/api/Hosts/SoftwareAssets/GetSoftwareAsssetsHeadersController/get", name="api_hosts_softwareassets_getsoftwareasssetsheaderscontroller_get", methods={"POST"})
     */
    public function get(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        return $this->fetchSoftwareAssetSnapshots->fetchLastSevenHeaders();
    }
}
