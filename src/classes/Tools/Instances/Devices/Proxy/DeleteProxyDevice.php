<?php

namespace dhope0000\LXDClient\Tools\Instances\Devices\Proxy;

use dhope0000\LXDClient\Objects\Host;

class DeleteProxyDevice
{
    public function delete(
        Host $host,
        string $instance,
        string $device
    ) {
        $info = $host->instances->info($instance);

        if (isset($info["devices"][$device]) && $info["devices"][$device]["type"] == "proxy") {
            unset($info["devices"][$device]);

            if (empty($info["devices"])) {
                unset($info["devices"]);
            }

            $host->instances->replace($instance, $info);
        }

        return true;
    }
}
