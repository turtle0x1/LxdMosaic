<?php

namespace dhope0000\LXDClient\Controllers\Backups;

use dhope0000\LXDClient\Tools\Backups\GetBackupsOverview;

class GetBackupsOverviewController
{
    private GetBackupsOverview $getBackupsOverview;

    public function __construct(GetBackupsOverview $getBackupsOverview)
    {
        $this->getBackupsOverview = $getBackupsOverview;
    }

    public function get(int $userId) :array
    {
        return $this->getBackupsOverview->get($userId);
    }
}
