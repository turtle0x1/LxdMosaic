<?php
namespace dhope0000\LXDClient\Controllers\Containers;

use dhope0000\LXDClient\Model\Containers\GetContainer;

class GetContainerDetailsController
{
    public function __construct(GetContainer $getContainer)
    {
        $this->getContainer = $getContainer;
    }

    public function get(string $host, string $container)
    {
        return $this->getContainer->get($host, $container);
    }
}
