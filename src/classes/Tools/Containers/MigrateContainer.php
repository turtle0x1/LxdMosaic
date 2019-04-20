<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class MigrateContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function migrate(
        int $hostId,
        string $container,
        int $newHostId,
        string $newContainerName
    ) {
        $hostClient = $this->lxdClient->getANewClient($hostId);
        $destinationClient = $this->lxdClient->getANewClient($newHostId);

        $hostClient->containers->migrate($destinationClient, $container, $newContainerName, true);
        return true;
    }
}
