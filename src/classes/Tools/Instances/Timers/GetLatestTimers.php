<?php

namespace dhope0000\LXDClient\Tools\Instances\Timers;

use dhope0000\LXDClient\Model\Hosts\Timers\FetchTimersSnapshot;
use dhope0000\LXDClient\Objects\Host;

class GetLatestTimers
{
    public function __construct(
        private readonly FetchTimersSnapshot $fetchTimersSnapshot
    ) {
    }

    public function get(Host $host, string $instance)
    {
        $snapshot = $this->fetchTimersSnapshot->fetchLatest();
        $timers = [];

        $data = json_decode((string) $snapshot['data'], true);

        if (isset($data[$host->getHostId()])) {
            $timers = $data[$host->getHostId()][$host->getProject()][$instance] ?? [];
        }

        return [
            'date' => $snapshot['date'],
            'timers' => $timers,
        ];

    }
}
