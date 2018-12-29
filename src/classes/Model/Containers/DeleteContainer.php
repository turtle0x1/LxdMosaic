<?php
namespace dhope0000\LXDClient\Model\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete($host, $container)
    {
        $lxd = $this->lxdClient->getClientByUrl($host);
        return $lxd->containers->remove($container);
    }
}
