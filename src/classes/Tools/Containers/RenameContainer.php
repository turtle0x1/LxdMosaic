<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RenameContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function rename($host, $container, $newContainer)
    {
        $lxd = $this->lxdClient->getClientByUrl($host);
        return $lxd->containers->rename($container, $newContainer, true);
    }
}
