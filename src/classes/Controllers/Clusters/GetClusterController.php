<?php

namespace dhope0000\LXDClient\Controllers\Clusters;

use dhope0000\LXDClient\Tools\Clusters\GetCluster;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class GetClusterController
{
    public function __construct(
        private readonly GetCluster $getCluster,
        private readonly ValidatePermissions $validatePermissions
    ) {
    }

    /**
     * @Route("/api/Clusters/GetClusterController/get", name="api_clusters_getclustercontroller_get", methods={"POST"})
     */
    public function get(int $userId, $cluster)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        return $this->getCluster->get($cluster);
    }
}
