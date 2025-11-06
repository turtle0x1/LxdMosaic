<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Storage\GetStoragePool;
use Symfony\Component\Routing\Attribute\Route;

class GetHostsStoragePoolController
{
    public function __construct(
        private readonly GetStoragePool $getStoragePool
    ) {
    }

    #[Route(path: '/api/Storage/GetHostsStoragePoolController/get', name: 'api_storage_gethostsstoragepoolcontroller_get', methods: ['POST'])]
    public function get(int $userId, Host $host, string $poolName)
    {
        return $this->getStoragePool->get($userId, $host, $poolName);
    }
}
