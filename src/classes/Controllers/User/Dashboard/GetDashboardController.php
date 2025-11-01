<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetUserDashboard;
use Symfony\Component\Routing\Annotation\Route;

class GetDashboardController
{
    private $getUserDashboard;
    
    public function __construct(GetUserDashboard $getUserDashboard)
    {
        $this->getUserDashboard = $getUserDashboard;
    }

    /**
     * @Route("/api/User/Dashboard/GetDashboardController/get", name="api_user_dashboard_getdashboardcontroller_get", methods={"POST"})
     */
    public function get(int $userId, int $dashboardId)
    {
        return $this->getUserDashboard->get($userId, $dashboardId);
    }
}
