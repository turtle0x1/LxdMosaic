<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class State
{
    public function change(Host $host, string $instance, string $state)
    {
        return $host->instances->{$state}($instance);
    }
}
