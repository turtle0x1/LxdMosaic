<?php

namespace dhope0000\LXDClient\Tools\Hosts\Instances;

use dhope0000\LXDClient\Constants\LxdRecursionLevels;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\GetAllInstanceProxies;

class GetAllProxyDevices
{
    public function __construct(
        private readonly GetAllInstanceProxies $getAllInstanceProxies
    ) {
    }

    public function get(Host $host)
    {
        // Recursion target?
        $instances = $host->instances->all(LxdRecursionLevels::INSTANCE_FULL_RECURSION);
        $output = [];
        foreach ($instances as $instance) {
            $devices = $this->getAllInstanceProxies->get($instance['devices']);

            if (!empty($devices)) {
                $output[$instance['name']] = $devices;
            }
        }
        return $output;
    }
}
