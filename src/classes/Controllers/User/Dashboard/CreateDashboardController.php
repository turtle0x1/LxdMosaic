<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Model\Users\Dashboard\InsertUserDashboard;
use Symfony\Component\Routing\Annotation\Route;

class CreateDashboardController
{
    private $insertUserDashboard;
    
    public function __construct(InsertUserDashboard $insertUserDashboard)
    {
        $this->insertUserDashboard = $insertUserDashboard;
    }

    /**
     * @Route("/api/User/Dashboard/CreateDashboardController/create", name="api_user_dashboard_createdashboardcontroller_create", methods={"POST"})
     */
    public function create(int $userId, string $name)
    {
        $this->insertUserDashboard->insert($userId, $name);
        return ["state"=>"success", "message"=>"Created dashbaord"];
    }
}
