<?php

namespace dhope0000\LXDClient\Tools\Storage\Volumes;

use dhope0000\LXDClient\Objects\Host;

class CreateVolume
{
    public function create(Host $host, string $pool, string $name, array $config)
    {
        return $host->storage->volumes->create($pool, $name, $config);
    }
}
