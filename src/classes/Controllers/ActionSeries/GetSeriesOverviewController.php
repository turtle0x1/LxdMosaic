<?php

namespace dhope0000\LXDClient\Controllers\ActionSeries;

use dhope0000\LXDClient\Tools\ActionSeries\GetSeriesOverview;

class GetSeriesOverviewController
{
    public function __construct(GetSeriesOverview $getSeriesOverview)
    {
        $this->getSeriesOverview = $getSeriesOverview;
    }

    public function get(int $actionSeries)
    {
        return  $this->getSeriesOverview->get($actionSeries);
    }
}
