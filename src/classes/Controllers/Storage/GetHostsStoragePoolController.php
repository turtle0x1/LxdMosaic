<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetStoragePool;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsStoragePoolController
{
    public function __construct(GetStoragePool $getStoragePool)
    {
        $this->getStoragePool = $getStoragePool;
    }
    /**
     * @Route("/api/Storage/GetHostsStoragePoolController/get", methods={"POST"}, name="Get stroage pool properties", options={"rbac" = "storage.pools.read"})
     */
    public function get(int $userId, Host $host, string $poolName)
    {
        return $this->getStoragePool->get($userId, $host, $poolName);
    }
}
