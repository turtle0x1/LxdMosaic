<?php

namespace dhope0000\LXDClient\Tools\Instances\Devices\Proxy;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteProxyDevice
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(
        int $hostId,
        string $instance,
        string $device
    ) {
        $client = $this->lxdClient->getANewClient($hostId);

        $info = $client->instances->info($instance);

        if (isset($info["devices"][$device]) && $info["devices"][$device]["type"] == "proxy") {
            unset($info["devices"][$device]);

            if (empty($info["devices"])) {
                unset($info["devices"]);
            }

            $client->instances->replace($instance, $info);
        }

        return true;
    }
}
