<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Tools\Containers\GetContainer;

class GetContainerDetailsController
{
    public function __construct(GetContainer $getContainer)
    {
        $this->getContainer = $getContainer;
    }

    public function get(int $hostId, string $container)
    {
        return $this->getContainer->get($hostId, $container);
    }
}
