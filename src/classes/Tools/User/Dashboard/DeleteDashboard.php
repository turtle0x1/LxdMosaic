<?php

namespace dhope0000\LXDClient\Tools\User\Dashboard;

use dhope0000\LXDClient\Model\Users\Dashboard\DeleteUserDashboard;

class DeleteDashboard
{
    private $deleteUserDashboard;

    public function __construct(DeleteUserDashboard $deleteUserDashboard)
    {
        $this->deleteUserDashboard = $deleteUserDashboard;
    }

    public function delete(int $userId, int $dashboardId)
    {
        //TODO Authenticate
        $this->deleteUserDashboard->delete($dashboardId);
    }
}
