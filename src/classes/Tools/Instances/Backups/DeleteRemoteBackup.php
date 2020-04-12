<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;
use dhope0000\LXDClient\Constants\LxdApiExtensions;

class DeleteRemoteBackup
{
    private $lxdClient;
    private $hasExtension;

    public function __construct(LxdClient $lxdClient, HasExtension $hasExtension)
    {
        $this->lxdClient = $lxdClient;
        $this->hasExtension = $hasExtension;
    }

    public function delete(int $hostId, string $instance, string $backup)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        if ($this->hasExtension->checkWithClient(
            $client,
            LxdApiExtensions::CONTAINER_BACKUP
        ) !== true) {
            throw new \Exception("Host doesn't support backups", 1);
        }

        return $client->instances->backups->remove($instance, $backup, [], true);
    }
}
