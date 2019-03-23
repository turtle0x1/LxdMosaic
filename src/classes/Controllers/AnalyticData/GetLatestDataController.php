<?php

namespace dhope0000\LXDClient\Controllers\AnalyticData;

use dhope0000\LXDClient\Tools\Analytics\GetLatestData;

class GetLatestDataController
{
    public function __construct(GetLatestData $getLatestData)
    {
        $this->getLatestData = $getLatestData;
    }

    public function get()
    {
        return  $this->getLatestData->get();
    }
}
