<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Containers\MigrateContainer;

class CopyContainer
{
    public function __construct(LxdClient $lxdClient, MigrateContainer $migrateContainer)
    {
        $this->lxdClient = $lxdClient;
        $this->migrateContainer = $migrateContainer;
    }

    public function copyContainer(
        int $hostId,
        string $container,
        string $newContainerName,
        int $newHostId
    ) {
        if ($hostId !== $newHostId) {
            $x = $this->migrateContainer->migrate($hostId, $container, $newHostId, $newContainerName);
            return $x;
        }
        $lxd = $this->lxdClient->getANewClient($hostId);
        return $lxd->containers->copy($container, $newContainer);
    }
}
