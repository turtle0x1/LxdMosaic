<?php
namespace dhope0000\LXDClient\Model\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class CopyContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function copyContainer($host, $container, $newContainer)
    {
        $lxd = $this->lxdClient->getClientByUrl($host);
        return $lxd->containers->copy($container, $newContainer);
    }
}
