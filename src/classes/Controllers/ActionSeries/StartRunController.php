<?php

namespace dhope0000\LXDClient\Controllers\ActionSeries;

use dhope0000\LXDClient\Tools\ActionSeries\Run\StartRun;

class StartRunController
{
    public function __construct(StartRun $startRun)
    {
        $this->startRun = $startRun;
    }

    public function start(int $userId, int $actionSeries, array $instancesByHost)
    {
        $this->startRun->start($userId, $actionSeries, $instancesByHost);

        return ["state"=>"success", "message"=>"Completed Run"];
    }
}
