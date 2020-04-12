<?php

namespace dhope0000\LXDClient\Tools\Instances\Files;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeletePath
{
    private $lxdClient;

    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, string $instance, string $path)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        return $client->instances->files->remove($instance, $path);
    }
}
