<?php

namespace dhope0000\LXDClient\Controllers\ActionSeries;

use dhope0000\LXDClient\Tools\ActionSeries\GetOverview;

class GetOverviewController
{
    public function __construct(GetOverview $getOverview)
    {
        $this->getOverview = $getOverview;
    }

    public function get()
    {
        return  $this->getOverview->get();
    }
}
