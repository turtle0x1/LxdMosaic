<?php

namespace dhope0000\LXDClient\Tools\ActionSeries;

use dhope0000\LXDClient\Model\ActionSeries\FetchSeries;

class GetOverview
{
    public function __construct(FetchSeries $fetchSeries)
    {
        $this->fetchSeries = $fetchSeries;
    }

    public function get()
    {
        return $this->fetchSeries->fetchAll();
    }
}
