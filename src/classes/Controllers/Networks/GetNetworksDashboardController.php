<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetNetworksDashboard;
use Symfony\Component\Routing\Annotation\Route;

class GetNetworksDashboardController
{
    public function __construct(GetNetworksDashboard $getNetworksDashboard)
    {
        $this->getNetworksDashboard = $getNetworksDashboard;
    }
    /**
     * @Route("/api/Networks/GetNetworksDashboardController/get", methods={"POST"}, name="Get dashboard data for networks", options={"rbac" = "networks.read"})
     */
    public function get(int $userId)
    {
        return $this->getNetworksDashboard->get($userId);
    }
}
