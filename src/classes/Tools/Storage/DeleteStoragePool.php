<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Objects\Host;

class DeleteStoragePool
{
    public function delete(Host $host, string $poolName)
    {
        return $host->storage->remove($poolName);
    }
}
