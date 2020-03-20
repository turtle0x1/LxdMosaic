<?php

namespace dhope0000\LXDClient\Tools\Hosts\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\GetAllInstanceProxies;

class GetAllProxyDevices
{
    public function __construct(
        LxdClient $lxdClient,
        GetAllInstanceProxies $getAllInstanceProxies
    ) {
        $this->lxdClient = $lxdClient;
        $this->getAllInstanceProxies = $getAllInstanceProxies;
    }

    public function get(int $hostId)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        $instances = $client->instances->all();

        $output = [];
        foreach ($instances as $instance) {
            $info = $client->instances->info($instance);

            $devices = $this->getAllInstanceProxies->get($info["devices"]);

            if (!empty($devices)) {
                $output[$instance] = $devices;
            }
        }
        return $output;
    }
}
