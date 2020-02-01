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
            return $this->migrateContainer->migrate(
                $hostId,
                $container,
                $newHostId,
                $newContainerName
            );
        }
        $client = $this->lxdClient->getANewClient($hostId);
        $r = $client->instances->copy($container, $newContainerName, [], true);
        // There is some error that is not being caught here so added this checking
        if (isset($r["err"]) && !empty($r["err"])) {
            throw new \Exception($r["err"], 1);
        }
        return $r;
    }
}
