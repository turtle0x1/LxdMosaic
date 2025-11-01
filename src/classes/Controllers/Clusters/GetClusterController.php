<?php

namespace dhope0000\LXDClient\Controllers\Clusters;

use dhope0000\LXDClient\Tools\Clusters\GetCluster;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use Symfony\Component\Routing\Annotation\Route;

class GetClusterController
{
    private $getCluster;
    private $validatePermissions;
    
    public function __construct(GetCluster $getCluster, ValidatePermissions $validatePermissions)
    {
        $this->getCluster = $getCluster;
        $this->validatePermissions = $validatePermissions;
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
