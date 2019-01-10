<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class SetContainerSettings
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function set(string $host, string $container, array $settings)
    {
        $client = $this->client->getClientByUrl($host);
        $client->containers->update($container, ["config"=>$settings]);
        return true;
    }
}
