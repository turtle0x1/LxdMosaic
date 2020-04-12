<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;

class SetInstanceSettings
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function set(int $hostId, string $instance, array $settings)
    {
        $client = $this->client->getANewClient($hostId);
        $client->instances->update($instance, ["config"=>$settings]);
        return true;
    }
}
