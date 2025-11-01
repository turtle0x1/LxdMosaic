<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetNetworksDashboard;
use Symfony\Component\Routing\Annotation\Route;

class GetNetworksDashboardController
{
    private $getNetworksDashboard;
    
    public function __construct(GetNetworksDashboard $getNetworksDashboard)
    {
        $this->getNetworksDashboard = $getNetworksDashboard;
    }

    /**
     * @Route("/api/Networks/GetNetworksDashboardController/get", name="api_networks_getnetworksdashboardcontroller_get", methods={"POST"})
     */
    public function get(int $userId)
    {
        return $this->getNetworksDashboard->get($userId);
    }
}
