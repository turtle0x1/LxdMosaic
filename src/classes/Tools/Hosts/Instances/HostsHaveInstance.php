<?php

namespace dhope0000\LXDClient\Tools\Hosts\Instances;

use dhope0000\LXDClient\Objects\HostsCollection;

class HostsHaveInstance
{
    public function ifHostInListHasContainerNameThrow(HostsCollection $hosts, string $name)
    {
        foreach ($hosts as $host) {
            $allContainers = $host->instances->all();
            if (in_array($name, $allContainers)) {
                throw new \Exception("Already have a container with the name {$name}", 1);
            }
        }
        return true;
    }
}
