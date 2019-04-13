<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class SetContainerSettings
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function set(int $hostId, string $container, array $settings)
    {
        $client = $this->client->getANewClient($hostId);
        $client->containers->update($container, ["config"=>$settings]);
        return true;
    }
}
