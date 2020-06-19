<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\EnablePullGathering;
use dhope0000\LXDClient\Objects\Host;

class EnablePullGatheringController
{
    public function __construct(EnablePullGathering $enablePullGathering)
    {
        $this->enablePullGathering = $enablePullGathering;
    }

    public function enable(Host $host, string $instance)
    {
        $this->enablePullGathering->enable($host, $instance);
        return ["state"=>"success", "message"=>"Enabled pulling metrics"];
    }
}
