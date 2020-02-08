<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\EnablePullGathering;

class EnablePullGatheringController
{
    public function __construct(EnablePullGathering $enablePullGathering)
    {
        $this->enablePullGathering = $enablePullGathering;
    }

    public function enable(int $host, string $instance)
    {
        $this->enablePullGathering->enable($host, $instance);
        return ["state"=>"success", "message"=>"Enabled pulling metrics"];
    }
}
