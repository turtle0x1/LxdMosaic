<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\DisablePullGathering;
use dhope0000\LXDClient\Objects\Host;

class DisablePullGatheringController
{
    private DisablePullGathering $disablePullGathering;

    public function __construct(DisablePullGathering $disablePullGathering)
    {
        $this->disablePullGathering = $disablePullGathering;
    }

    public function disable(Host $host, string $instance, int $clearData = 0)
    {
        $this->disablePullGathering->disable($host, $instance, (bool) $clearData);
        return ["state"=>"success", "message"=>"Disabled pulling metrics"];
    }
}
