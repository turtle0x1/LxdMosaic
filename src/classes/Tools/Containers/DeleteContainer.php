<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, string $container)
    {
        $lxd = $this->lxdClient->getANewClient($hostId);
        return $lxd->containers->remove($container);
    }
}
