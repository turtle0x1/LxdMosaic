<?php

namespace dhope0000\LXDClient\Controllers\Clusters;

use dhope0000\LXDClient\Tools\Clusters\GetCluster;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class GetClusterController
{
    public function __construct(GetCluster $getCluster, ValidatePermissions $validatePermissions)
    {
        $this->getCluster = $getCluster;
        $this->validatePermissions = $validatePermissions;
    }

    public function get(int $userId, $cluster)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $this->getCluster->get($cluster);
    }
}
