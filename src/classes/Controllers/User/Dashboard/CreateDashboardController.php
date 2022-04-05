<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Model\Users\Dashboard\InsertUserDashboard;
use Symfony\Component\Routing\Annotation\Route;

class CreateDashboardController
{
    public function __construct(InsertUserDashboard $insertUserDashboard)
    {
        $this->insertUserDashboard = $insertUserDashboard;
    }
    /**
     * @Route("/api/User/Dashboard/CreateDashboardController/create", methods={"POST"}, name="Create user dashboard", options={"rbac" = "user.dashboard.create"})
     */
    public function create(int $userId, string $name)
    {
        $this->insertUserDashboard->insert($userId, $name);
        return ["state"=>"success", "message"=>"Created dashbaord"];
    }
}
