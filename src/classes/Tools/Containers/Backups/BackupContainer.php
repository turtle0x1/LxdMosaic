<?php

namespace dhope0000\LXDClient\Tools\Containers\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;

class BackupContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function create(int $hostId, string $container, string $backup, $wait)
    {
        if ($wait === "false") {
            $wait = false;
        }
        $client = $this->lxdClient->getANewClient($hostId);

        return $client->containers->backups->create($container, $backup, [], $wait);
    }
}
