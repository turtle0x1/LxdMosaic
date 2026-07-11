<?php

namespace dhope0000\LXDClient\Controllers\Instances\Metrics;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Metrics\EnablePullGathering;
use Symfony\Component\Routing\Attribute\Route;

class EnablePullGatheringController
{
    public function __construct(
        private readonly EnablePullGathering $enablePullGathering
    ) {
    }

    #[Route(path: '/api/Instances/Metrics/EnablePullGatheringController/enable', name: 'Enable metrics gathering', methods: ['POST'])]
    public function enable(Host $host, string $instance)
    {
        $this->enablePullGathering->enable($host, $instance);
        return [
            'state' => 'success',
            'message' => 'Enabled pulling metrics',
        ];
    }
}
