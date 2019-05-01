<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetStoragePool;

class GetHostsStoragePoolController
{
    public function __construct(GetStoragePool $getStoragePool)
    {
        $this->getStoragePool = $getStoragePool;
    }

    public function get(int $hostId, string $poolName){
        return $this->getStoragePool->get($hostId, $poolName);
    }
}
