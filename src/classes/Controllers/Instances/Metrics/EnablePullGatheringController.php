<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\EnablePullGathering;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class EnablePullGatheringController
{
    public function __construct(EnablePullGathering $enablePullGathering)
    {
        $this->enablePullGathering = $enablePullGathering;
    }
    /**
     * @Route("/api/Instances/Metrics/EnablePullGatheringController/enable", methods={"POST"}, name="Enable gathering metrics for instance")
     */
    public function enable(Host $host, string $instance)
    {
        $this->enablePullGathering->enable($host, $instance);
        return ["state"=>"success", "message"=>"Enabled pulling metrics"];
    }
}
