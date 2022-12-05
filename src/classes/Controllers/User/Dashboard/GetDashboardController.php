<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Tools\Instances\Metrics\GetUserDashboard;

class GetDashboardController
{
    private GetUserDashboard $getUserDashboard;

    public function __construct(GetUserDashboard $getUserDashboard)
    {
        $this->getUserDashboard = $getUserDashboard;
    }

    public function get(int $userId, int $dashboardId) :array
    {
        return $this->getUserDashboard->get($userId, $dashboardId);
    }
}
