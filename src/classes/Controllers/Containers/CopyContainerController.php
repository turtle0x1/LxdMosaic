<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Tools\Containers\CopyContainer;

class CopyContainerController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(CopyContainer $copyContainer)
    {
        $this->copyContainer = $copyContainer;
    }

    public function copyContainer(
        int $hostId,
        string $container,
        string $newContainer,
        int $newHostId,
        string $alias = null
    ) {
        $this->copyContainer->copyContainer($hostId, $container, $newContainer, $newHostId);
        return ["state"=>"success", "message"=>"Copying $container to $newContainer"];
    }
}
