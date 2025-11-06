<?php

namespace dhope0000\LXDClient\Controllers\Backups;

use dhope0000\LXDClient\Tools\Backups\GetBackupsOverview;
use Symfony\Component\Routing\Annotation\Route;

class GetBackupsOverviewController
{
    public function __construct(
        private readonly GetBackupsOverview $getBackupsOverview
    ) {
    }

    /**
     * @Route("/api/Backups/GetBackupsOverviewController/get", name="api_backups_getbackupsoverviewcontroller_get", methods={"POST"})
     */
    public function get($userId)
    {
        return $this->getBackupsOverview->get($userId);
    }
}
