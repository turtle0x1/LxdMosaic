<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Model\Containers\RenameContainer;

class RenameContainerController
{
    public function __construct(RenameContainer $renameContainer)
    {
        $this->renameContainer = $renameContainer;
    }

    public function renameContainer($host, $container, $newContainer)
    {
        $result = $this->renameContainer->rename($host, $container, $newContainer);
        return [
            "state"=>"success",
            "message"=>"Renaming $host/$container to $host/$newContainer",
            "lxdResponse"=>$result
        ];
    }
}
