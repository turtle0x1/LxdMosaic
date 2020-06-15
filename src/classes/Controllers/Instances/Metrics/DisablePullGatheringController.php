<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\DisablePullGathering;
use dhope0000\LXDClient\Objects\Host;

class DisablePullGatheringController
{
    public function __construct(DisablePullGathering $disablePullGathering)
    {
        $this->disablePullGathering = $disablePullGathering;
    }

    public function disable(Host $host, string $instance)
    {
        $this->disablePullGathering->disable($host, $instance);
        return ["state"=>"success", "message"=>"Disabled pulling metrics"];
    }
}
