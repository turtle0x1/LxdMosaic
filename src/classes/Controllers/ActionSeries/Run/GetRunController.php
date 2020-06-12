<?php

namespace dhope0000\LXDClient\Controllers\ActionSeries\Run;

use dhope0000\LXDClient\Tools\ActionSeries\Run\GetRun;

class GetRunController
{
    public function __construct(GetRun $getRun)
    {
        $this->getRun = $getRun;
    }

    public function get(int $runId)
    {
        return $this->getRun->get($runId);
    }
}
