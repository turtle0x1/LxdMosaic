<?php

namespace dhope0000\LXDClient\Controllers\Containers\Files;

use dhope0000\LXDClient\Tools\Containers\Files\DeletePath;

class DeletePathController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $deletePath;

    public function __construct(DeletePath $deletePath)
    {
        $this->deletePath = $deletePath;
    }

    public function delete(
        int $hostId,
        string $container,
        string $path
    ) {
        $response = $this->deletePath->delete($hostId, $container, $path);

        return ["state"=>"success", "message"=>"Deleted item", "lxdResponse"=>$response];
    }
}
