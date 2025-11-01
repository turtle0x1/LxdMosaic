<?php

namespace dhope0000\LXDClient\Controllers\Dashboard;

use dhope0000\LXDClient\Tools\Dashboard\GetDashboard;
use Symfony\Component\Routing\Annotation\Route;

class GetController
{
    private $getDashboard;
    
    public function __construct(GetDashboard $getDashboard)
    {
        $this->getDashboard = $getDashboard;
    }

    /**
     * @Route("/api/Dashboard/GetController/get", name="api_dashboard_getcontroller_get", methods={"POST"})
     */
    public function get($userId, string $history = "-30 minutes")
    {
        return $this->getDashboard->get($userId);
    }
}
