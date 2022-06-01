<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\CreateStoragePool;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class CreatePoolController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $createStoragePool;
    
    public function __construct(CreateStoragePool $createStoragePool)
    {
        $this->createStoragePool = $createStoragePool;
    }
    /**
     * @Route("", name="Create Storage")
     */
    public function create(HostsCollection $hosts, string $name, string $driver, array $config)
    {
        $this->createStoragePool->create($hosts, $name, $driver, $config);
        return ["state"=>"success", "message"=>"Created Pools"];
    }
}
