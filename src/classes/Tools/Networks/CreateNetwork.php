<?php

namespace dhope0000\LXDClient\Tools\Networks;

use dhope0000\LXDClient\Objects\HostsCollection;

class CreateNetwork
{
    public function create(HostsCollection $hosts, string $name, string $description = "", array $config = [])
    {
        foreach ($hosts as $host) {
            $host->networks->create($name, $description, $config);
        }

        return true;
    }
}
