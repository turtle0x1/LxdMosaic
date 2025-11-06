<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Metrics\DisablePullGathering;
use Symfony\Component\Routing\Attribute\Route;

class DisablePullGatheringController
{
    public function __construct(
        private readonly DisablePullGathering $disablePullGathering
    ) {
    }

    #[Route(path: '/api/Instances/Metrics/DisablePullGatheringController/disable', name: 'api_instances_metrics_disablepullgatheringcontroller_disable', methods: ['POST'])]
    public function disable(Host $host, string $instance, int $clearData = 0)
    {
        $this->disablePullGathering->disable($host, $instance, (bool) $clearData);
        return [
            'state' => 'success',
            'message' => 'Disabled pulling metrics',
        ];
    }
}
