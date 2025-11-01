<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Tools\User\Dashboard\DeleteDashboard;
use Symfony\Component\Routing\Annotation\Route;

class DeleteDashboardController
{
    private $deleteDashboard;

    public function __construct(DeleteDashboard $deleteDashboard)
    {
        $this->deleteDashboard = $deleteDashboard;
    }

    /**
     * @Route("/api/User/Dashboard/DeleteDashboardController/delete", name="api_user_dashboard_deletedashboardcontroller_delete", methods={"POST"})
     */
    public function delete(int $userId, int $dashboardId)
    {
        $this->deleteDashboard->delete($userId, $dashboardId);
        return ["state"=>"success", "message"=>"Deleted dashboard"];
    }
}
