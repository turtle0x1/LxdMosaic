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

    public function copyContainer($host, $container, $newContainerName, $newHost)
    {
        if ($host !== $newHost) {
            $x = $this->migrateContainer->migrate($host, $container, $newHost, $newContainerName);
            return $x;
        }
        $lxd = $this->lxdClient->getClientByUrl($host);
        return $lxd->containers->copy($container, $newContainer);
    }
}
