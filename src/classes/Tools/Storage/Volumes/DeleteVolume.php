<?php

namespace dhope0000\LXDClient\Tools\Storage\Volumes;

use dhope0000\LXDClient\Objects\Host;

class DeleteVolume
{
    public function delete(Host $host, string $pool, string $path)
    {
        return $host->storage->volumes->remove($pool, $path);
    }
}
