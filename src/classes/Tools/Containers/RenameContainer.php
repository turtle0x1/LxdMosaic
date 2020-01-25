<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RenameContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function rename(int $hostId, string $container, string $newContainer)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->instances->rename($container, $newContainer, true);
    }
}
