<?php

namespace dhope0000\LXDClient\Controllers\Backups;

use dhope0000\LXDClient\Tools\Backups\GetBackupsOverview;

class GetBackupsOverviewController
{
    private $getBackupsOverview;
    
    public function __construct(GetBackupsOverview $getBackupsOverview)
    {
        $this->getBackupsOverview = $getBackupsOverview;
    }

    public function get()
    {
        return $this->getBackupsOverview->get();
    }
}
