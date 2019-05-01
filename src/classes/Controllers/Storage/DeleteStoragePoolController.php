<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\DeleteStoragePool;

class DeleteStoragePoolController
{
    public function __construct(DeleteStoragePool $deleteStoragePool)
    {
        $this->deleteStoragePool = $deleteStoragePool;
    }

    public function delete(int $hostId, string $poolName){
        $this->deleteStoragePool->delete($hostId, $poolName);
        return ["state"=>"success", "message"=>"Deleted Pool"];
    }
}
