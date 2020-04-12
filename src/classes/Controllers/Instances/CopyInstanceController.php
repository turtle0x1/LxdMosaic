<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\Copy;

class CopyInstanceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(Copy $copy)
    {
        $this->copy = $copy;
    }

    public function copy(
        int $hostId,
        string $container,
        string $newContainer,
        int $newHostId,
        string $alias = null
    ) {
        $this->copy->copy($hostId, $container, $newContainer, $newHostId);
        return ["state"=>"success", "message"=>"Copying $container to $newContainer"];
    }
}
