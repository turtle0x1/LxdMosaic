<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetUserOverview;

class GetUserOverviewController
{
    public function __construct(GetUserOverview $getUserOverview)
    {
        $this->getUserOverview = $getUserOverview;
    }

    public function get(int $userId, int $targetUser)
    {
        return $this->getUserOverview->get($userId, $targetUser);
    }
}
