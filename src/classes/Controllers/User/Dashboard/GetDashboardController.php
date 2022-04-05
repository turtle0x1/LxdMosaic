<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetUserDashboard;
use Symfony\Component\Routing\Annotation\Route;

class GetDashboardController
{
    public function __construct(GetUserDashboard $getUserDashboard)
    {
        $this->getUserDashboard = $getUserDashboard;
    }
    /**
     * @Route("/api/User/Dashboard/GetDashboardController/get", methods={"POST"}, name="Get user dashboard", options={"rbac" = "user.dashboard.read"})
     */
    public function get(int $userId, int $dashboardId)
    {
        return $this->getUserDashboard->get($userId, $dashboardId);
    }
}
