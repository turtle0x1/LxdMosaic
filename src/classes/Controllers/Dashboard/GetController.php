<?php

namespace dhope0000\LXDClient\Controllers\Dashboard;

use dhope0000\LXDClient\Tools\Dashboard\GetDashboard;

class GetController
{
    public function __construct(GetDashboard $getDashboard)
    {
        $this->getDashboard = $getDashboard;
    }

    public function get($userId)
    {
        return $this->getDashboard->get($userId);
    }
}
