<?php

namespace dhope0000\LXDClient\Controllers\Networks;

use dhope0000\LXDClient\Tools\Networks\GetNetworksDashboard;

class GetNetworksDashboardController
{
    private GetNetworksDashboard $getNetworksDashboard;

    public function __construct(GetNetworksDashboard $getNetworksDashboard)
    {
        $this->getNetworksDashboard = $getNetworksDashboard;
    }

    public function get(int $userId) :array
    {
        return $this->getNetworksDashboard->get($userId);
    }
}
