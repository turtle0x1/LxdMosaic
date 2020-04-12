<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;

class Migrate
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function migrate(
        int $hostId,
        string $instance,
        int $newHostId,
        string $newContainerName
    ) {
        $hostClient = $this->lxdClient->getANewClient($hostId);
        $destinationClient = $this->lxdClient->getANewClient($newHostId);

        $hostClient->instances->migrate($destinationClient, $instance, $newContainerName, true);
        return true;
    }
}
