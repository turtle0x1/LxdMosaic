<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;

class SetInstanceSettings
{
    public function set(Host $host, string $instance, array $settings)
    {
        $host->instances->update($instance, [
            'config' => $settings,
        ]);
        return true;
    }
}
