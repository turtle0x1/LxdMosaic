<?php

namespace dhope0000\LXDClient\Tools\ActionSeries;

use dhope0000\LXDClient\Model\ActionSeries\FetchSeries;
use dhope0000\LXDClient\Tools\ActionSeries\Commands\GetCommandTree;
use dhope0000\LXDClient\Model\ActionSeries\Run\FetchRuns;

class GetSeriesOverview
{
    public function __construct(
        FetchSeries $fetchSeries,
        GetCommandTree $getCommandTree,
        FetchRuns $fetchRuns
    ) {
        $this->fetchSeries = $fetchSeries;
        $this->getCommandTree = $getCommandTree;
        $this->fetchRuns = $fetchRuns;
    }

    public function get(int $actionSeries)
    {
        $details = $this->fetchSeries->fetchDetails($actionSeries);

        $commandTree = $this->getCommandTree->get($actionSeries);

        $runs = $this->fetchRuns->fetchForSeries($actionSeries);

        return [
            "details"=>$details,
            "commandTree"=>$commandTree,
            "runs"=>$runs
        ];
    }
}
