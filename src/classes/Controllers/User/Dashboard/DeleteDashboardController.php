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
     * @Route("/api/User/Dashboard/DeleteDashboardController/delete", methods={"POST"}, name="Delete user dashboard")
     */
    public function delete(int $userId, int $dashboardId)
    {
        $this->deleteDashboard->delete($userId, $dashboardId);
        return ["state"=>"success", "message"=>"Deleted dashboard"];
    }
}
