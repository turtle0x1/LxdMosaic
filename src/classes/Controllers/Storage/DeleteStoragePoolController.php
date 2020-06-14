<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Tools\Storage\DeleteStoragePool;
use dhope0000\LXDClient\Objects\Host;

class DeleteStoragePoolController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(DeleteStoragePool $deleteStoragePool)
    {
        $this->deleteStoragePool = $deleteStoragePool;
    }

    public function delete(Host $host, string $poolName)
    {
        $this->deleteStoragePool->delete($host, $poolName);
        return ["state"=>"success", "message"=>"Deleted Pool"];
    }
}
