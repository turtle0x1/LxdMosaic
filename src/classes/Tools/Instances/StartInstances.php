<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class StartInstances
{
    public function start(Host $host, array $instances)
    {
        foreach ($instances as $instance) {
            $state = $host->instances->state($instance);

            if ($state['status_code'] == 103) {
                continue;
            }

            $host->instances->setState($instance, 'start');
        }

        return true;
    }
}
