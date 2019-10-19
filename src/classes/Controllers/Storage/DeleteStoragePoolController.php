<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\DeleteStoragePool;

class DeleteStoragePoolController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteStoragePool $deleteStoragePool)
    {
        $this->deleteStoragePool = $deleteStoragePool;
    }

    public function delete(int $hostId, string $poolName)
    {
        $this->deleteStoragePool->delete($hostId, $poolName);
        return ["state"=>"success", "message"=>"Deleted Pool"];
    }
}
