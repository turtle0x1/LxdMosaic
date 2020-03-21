<?php

namespace dhope0000\LXDClient\Tools\Instances\Devices\Proxy;

use dhope0000\LXDClient\Model\Client\LxdClient;

class AddProxyDevice
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function add(
        int $hostId,
        string $instance,
        string $proxyName,
        string $source,
        string $destination
    ) {
        $client = $this->lxdClient->getANewClient($hostId);

        $devices = [
            $proxyName=>[
                "listen"=>$source,
                "connect"=>$destination,
                "type"=>"proxy"
            ]
        ];

        $devices = $client->instances->update($instance, [
            "devices"=>$devices
        ]);

        return true;
    }
}
