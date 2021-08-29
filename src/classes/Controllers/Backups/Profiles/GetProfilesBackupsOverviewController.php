<?php

namespace dhope0000\LXDClient\Controllers\Backups\Profiles;

use dhope0000\LXDClient\Tools\Backups\Profiles\GetProfilesBackupsOverview;

class GetProfilesBackupsOverviewController
{
    private $getProfilesBackupsOverview;

    public function __construct(GetProfilesBackupsOverview $getProfilesBackupsOverview)
    {
        $this->getProfilesBackupsOverview = $getProfilesBackupsOverview;
    }

    public function get($userId)
    {
        return $this->getProfilesBackupsOverview->get($userId);
    }
}
