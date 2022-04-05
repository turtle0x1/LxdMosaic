<?php

namespace dhope0000\LXDClient\Controllers\Backups;

use dhope0000\LXDClient\Tools\Backups\GetBackupsOverview;
use Symfony\Component\Routing\Annotation\Route;

class GetBackupsOverviewController
{
    private $getBackupsOverview;

    public function __construct(GetBackupsOverview $getBackupsOverview)
    {
        $this->getBackupsOverview = $getBackupsOverview;
    }
    /**
     * @Route("/api/Backups/GetBackupsOverviewController/get", methods={"POST"}, name="Get an overview of instance backups", options={"rbac" = "backups.read"})
     */
    public function get($userId)
    {
        return $this->getBackupsOverview->get($userId);
    }
}
