<?php

namespace dhope0000\LXDClient\Controllers\Instances\Timers;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Timers\GetLatestTimers;
use Symfony\Component\Routing\Annotation\Route;

class GetLatestTimersController
{
    public function __construct(
        private readonly GetLatestTimers $getLatestTimers
    ) {
    }

    /**
     * @Route("/api/instances/timers/latest", name="Get instance latest timers (systemd/cron)", methods={"POST", "GET"})
     */
    public function get(Host $host, string $container)
    {
        return $this->getLatestTimers->get($host, $container);
    }
}
