<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Client\LxdClient;

class HostsHaveContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function ifHostInListHasContainerNameThrow($hosts, $name)
    {
        foreach ($hosts as $host) {
            $client = $this->client->getClientByUrl($host);
            $allContainers = $client->containers->all();
            if (in_array($name, $allContainers)) {
                throw new \Exception("$host has a container with the name $name", 1);
            }
        }
        return true;
    }
}
