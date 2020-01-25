<?php

namespace dhope0000\LXDClient\Tools\Containers\Files;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeletePath
{
    private $lxdClient;

    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, string $container, string $path)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        return $client->instances->files->remove($container, $path);
    }
}
