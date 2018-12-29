<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Model\Containers\CopyContainer;

class CopyContainerController
{
    public function __construct(CopyContainer $copyContainer)
    {
        $this->copyContainer = $copyContainer;
    }

    public function copyContainer($host, $container, $newContainer)
    {
        $this->copyContainer->copyContainer($host, $container, $newContainer);
        return ["state"=>"success", "message"=>"Copying $container to $host / $newContainer"];
    }
}
