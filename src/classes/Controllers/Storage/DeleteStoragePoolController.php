<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\DeleteStoragePool;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class DeleteStoragePoolController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private DeleteStoragePool $deleteStoragePool;

    public function __construct(DeleteStoragePool $deleteStoragePool)
    {
        $this->deleteStoragePool = $deleteStoragePool;
    }
    /**
     * @Route("", name="Delete Storage")
     */
    public function delete(Host $host, string $poolName)
    {
        $this->deleteStoragePool->delete($host, $poolName);
        return ["state"=>"success", "message"=>"Deleted Pool"];
    }
}
