<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Tools\Containers\RenameContainer;

class RenameContainerController
{
    public function __construct(RenameContainer $renameContainer)
    {
        $this->renameContainer = $renameContainer;
    }

    public function renameContainer(int $hostId, string $container, string $newContainer)
    {
        $result = $this->renameContainer->rename($hostId, $container, $newContainer);
        return [
            "state"=>"success",
            "message"=>"Renaming $host/$container to $host/$newContainer",
            "lxdResponse"=>$result
        ];
    }
}
