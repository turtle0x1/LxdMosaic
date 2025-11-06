<?php

namespace dhope0000\LXDClient\Tools\User\Dashboard;

use dhope0000\LXDClient\Model\Users\Dashboard\DeleteUserDashboard;

class DeleteDashboard
{
    public function __construct(
        private readonly DeleteUserDashboard $deleteUserDashboard
    ) {
    }

    public function delete(int $userId, int $dashboardId)
    {
        //TODO Authenticate
        $this->deleteUserDashboard->delete($dashboardId);
    }
}
