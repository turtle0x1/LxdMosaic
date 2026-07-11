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

    #[Route(path: '/api/User/Dashboard/GetDashboardController/get', name: 'Get user dashboard', methods: ['POST'])]
    public function get(int $userId, int $dashboardId)
    {
        return $this->getUserDashboard->get($userId, $dashboardId);
    }
}
