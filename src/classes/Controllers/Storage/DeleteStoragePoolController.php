<?php

namespace dhope0000\LXDClient\Controllers\Storage;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Storage\DeleteStoragePool;
use Symfony\Component\Routing\Annotation\Route;

class DeleteStoragePoolController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly DeleteStoragePool $deleteStoragePool
    ) {
    }

    /**
     * @Route("/api/Storage/DeleteStoragePoolController/delete", name="Delete Storage", methods={"POST"})
     */
    public function delete(Host $host, string $poolName)
    {
        $this->deleteStoragePool->delete($host, $poolName);
        return [
            'state' => 'success',
            'message' => 'Deleted Pool',
        ];
    }
}
