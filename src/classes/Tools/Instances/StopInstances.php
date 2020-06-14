<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class StopInstances
{
    public function stop(Host $host, array $instances)
    {
        foreach ($instances as $instance) {
            $state = $host->instances->state($instance);

            if ($state["status_code"] == 102) {
                continue;
            }

            $host->instances->setState($instance, "stop");
        }

        return true;
    }
}
