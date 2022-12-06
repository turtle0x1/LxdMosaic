<?php

namespace dhope0000\LXDClient\Tools\Hosts\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Devices\Proxy\GetAllInstanceProxies;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;

class GetAllProxyDevices
{
    private GetAllInstanceProxies $getAllInstanceProxies;

    public function __construct(GetAllInstanceProxies $getAllInstanceProxies)
    {
        $this->getAllInstanceProxies = $getAllInstanceProxies;
    }

    public function get(Host $host) :array
    {
        // Recursion target?
        $instances = $host->instances->all(LxdRecursionLevels::INSTANCE_FULL_RECURSION);
        $output = [];
        foreach ($instances as $instance) {
            $devices = $this->getAllInstanceProxies->get($instance["devices"]);

            if (!empty($devices)) {
                $output[$instance["name"]] = $devices;
            }
        }
        return $output;
    }
}
