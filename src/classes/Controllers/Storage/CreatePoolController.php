<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Storage\CreateStoragePool;
use Symfony\Component\Routing\Attribute\Route;

class CreatePoolController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly CreateStoragePool $createStoragePool
    ) {
    }

    #[Route(path: '/api/Storage/CreatePoolController/create', name: 'Create Storage', methods: ['POST'])]
    public function create(HostsCollection $hosts, string $name, string $driver, array $config)
    {
        $this->createStoragePool->create($hosts, $name, $driver, $config);
        return [
            'state' => 'success',
            'message' => 'Created Pools',
        ];
    }
}
