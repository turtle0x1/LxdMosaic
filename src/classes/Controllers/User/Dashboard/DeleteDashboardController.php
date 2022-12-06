<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Tools\User\Dashboard\DeleteDashboard;

class DeleteDashboardController
{
    private DeleteDashboard $deleteDashboard;

    public function __construct(DeleteDashboard $deleteDashboard)
    {
        $this->deleteDashboard = $deleteDashboard;
    }

    public function delete(int $userId, int $dashboardId) :array
    {
        $this->deleteDashboard->delete($userId, $dashboardId);
        return ["state"=>"success", "message"=>"Deleted dashboard"];
    }
}
