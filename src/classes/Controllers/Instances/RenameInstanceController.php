<?php
namespace dhope0000\LXDClient\Controllers\Instances;

use dhope0000\LXDClient\Tools\Instances\RenameInstance;

class RenameInstanceController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(RenameInstance $renameInstance)
    {
        $this->renameInstance = $renameInstance;
    }

    public function rename(
        int $hostId,
        string $container,
        string $newContainer,
        string $alias = null
    ) {
        $result = $this->renameInstance->rename($hostId, $container, $newContainer);
        return [
            "state"=>"success",
            "message"=>"Renaming $alias/$container to $alias/$newContainer",
            "lxdResponse"=>$result
        ];
    }
}
