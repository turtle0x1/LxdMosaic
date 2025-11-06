<?php

namespace dhope0000\LXDClient\Tools\Instances\Devices\Proxy;

class GetAllInstanceProxies
{
    public function get(array $devices)
    {
        $output = [];
        foreach ($devices as $name => $device) {
            if (isset($device['type']) && $device['type'] !== 'proxy') {
                continue;
            }

            $output[$name] = $device;
        }
        return $output;
    }
}
