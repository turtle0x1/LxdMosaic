<?php

namespace dhope0000\LXDClient\Tools\Containers\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteRemoteBackup
{
    private $lxdClient;
    
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, string $container, string $backup)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        return $client->containers->backups->remove($container, $backup, [], true);
    }
}
