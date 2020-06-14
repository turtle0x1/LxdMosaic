<?php

namespace dhope0000\LXDClient\Tools\Instances\Devices\Proxy;

use dhope0000\LXDClient\Objects\Host;

class AddProxyDevice
{
    public function add(
        Host $host,
        string $instance,
        string $proxyName,
        string $source,
        string $destination
    ) {
        $devices = [
            $proxyName=>[
                "listen"=>$source,
                "connect"=>$destination,
                "type"=>"proxy"
            ]
        ];

        $devices = $host->instances->update($instance, [
            "devices"=>$devices
        ]);

        return true;
    }
}
