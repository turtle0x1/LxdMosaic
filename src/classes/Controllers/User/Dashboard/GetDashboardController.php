<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetUserDashboard;
use Symfony\Component\Routing\Attribute\Route;

class GetDashboardController
{
    public function __construct(
        private readonly GetUserDashboard $getUserDashboard
    ) {
    }

    #[Route(path: '/api/User/Dashboard/GetDashboardController/get', name: 'api_user_dashboard_getdashboardcontroller_get', methods: ['POST'])]
    public function get(int $userId, int $dashboardId)
    {
        return $this->getUserDashboard->get($userId, $dashboardId);
    }
}
