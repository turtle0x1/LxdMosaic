<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetStoragePool;
use dhope0000\LXDClient\Objects\Host;

class GetHostsStoragePoolController
{
    private $getStoragePool;
    
    public function __construct(GetStoragePool $getStoragePool)
    {
        $this->getStoragePool = $getStoragePool;
    }

    public function get(int $userId, Host $host, string $poolName)
    {
        return $this->getStoragePool->get($userId, $host, $poolName);
    }
}
