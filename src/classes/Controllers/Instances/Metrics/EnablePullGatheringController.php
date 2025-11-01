<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\EnablePullGathering;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class EnablePullGatheringController
{
    private $enablePullGathering;
    
    public function __construct(EnablePullGathering $enablePullGathering)
    {
        $this->enablePullGathering = $enablePullGathering;
    }

    /**
     * @Route("/api/Instances/Metrics/EnablePullGatheringController/enable", name="api_instances_metrics_enablepullgatheringcontroller_enable", methods={"POST"})
     */
    public function enable(Host $host, string $instance)
    {
        $this->enablePullGathering->enable($host, $instance);
        return ["state"=>"success", "message"=>"Enabled pulling metrics"];
    }
}
