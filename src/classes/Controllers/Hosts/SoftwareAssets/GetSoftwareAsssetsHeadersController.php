<?php

namespace dhope0000\LXDClient\Controllers\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Model\Hosts\SoftwareAssets\FetchSoftwareAssetSnapshots;

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

    public function get(int $userId)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId) === '1';
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        return $this->fetchSoftwareAssetSnapshots->fetchLastSevenHeaders();
    }
}
