<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\CreateStoragePool;
use dhope0000\LXDClient\Objects\HostsCollection;

class CreatePoolController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CreateStoragePool $createStoragePool)
    {
        $this->createStoragePool = $createStoragePool;
    }

    public function create(HostsCollection $hosts, string $name, string $driver, array $config)
    {
        $this->createStoragePool->create($hosts, $name, $driver, $config);
        return ["state"=>"success", "message"=>"Created Pools"];
    }
}
