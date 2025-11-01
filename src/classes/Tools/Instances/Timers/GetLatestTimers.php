<?php

namespace dhope0000\LXDClient\Tools\Instances\Timers;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Hosts\Timers\FetchTimersSnapshot;

class GetLatestTimers
{
    private $fetchTimersSnapshot;

    public function __construct(FetchTimersSnapshot $fetchTimersSnapshot)
    {
        $this->fetchTimersSnapshot = $fetchTimersSnapshot;
    }

    public function get(Host $host, string $instance)
    {
        $snapshot = $this->fetchTimersSnapshot->fetchLatest();
        $timers = [];
        
        $data = json_decode($snapshot["data"], true);

        if(isset($data[$host->getHostId()])){
            $timers = $data[$host->getHostId()][$host->getProject()][$instance] ?? [];
        }
        
        return [
            "date"=>$snapshot["date"],
            "timers"=>$timers
        ];
        
    }
}
