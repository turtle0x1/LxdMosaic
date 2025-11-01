<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\GetStoragePool;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class GetHostsStoragePoolController
{
    private $getStoragePool;
    
    public function __construct(GetStoragePool $getStoragePool)
    {
        $this->getStoragePool = $getStoragePool;
    }

    /**
     * @Route("/api/Storage/GetHostsStoragePoolController/get", name="api_storage_gethostsstoragepoolcontroller_get", methods={"POST"})
     */
    public function get(int $userId, Host $host, string $poolName)
    {
        return $this->getStoragePool->get($userId, $host, $poolName);
    }
}
