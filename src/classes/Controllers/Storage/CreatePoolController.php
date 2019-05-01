<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\CreateStoragePool;

class CreatePoolController
{
    public function __construct(CreateStoragePool $createStoragePool)
    {
        $this->createStoragePool = $createStoragePool;
    }

    public function create(array $hosts, string $name, string $driver, array $config){
        $this->createStoragePool->create($hosts, $name, $driver, $config);
        return ["state"=>"success", "message"=>"Created Pools"];
    }
}
