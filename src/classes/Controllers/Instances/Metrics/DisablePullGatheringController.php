<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\DisablePullGathering;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DisablePullGatheringController
{
    public function __construct(DisablePullGathering $disablePullGathering)
    {
        $this->disablePullGathering = $disablePullGathering;
    }
    /**
     * @Route("/api/Instances/Metrics/DisablePullGatheringController/disable", methods={"POST"}, name="Disable gathering metrics for instance", options={"rbac" = "instances.metrics.disable"})
     */
    public function disable(Host $host, string $instance, int $clearData = 0)
    {
        $this->disablePullGathering->disable($host, $instance, (bool) $clearData);
        return ["state"=>"success", "message"=>"Disabled pulling metrics"];
    }
}
