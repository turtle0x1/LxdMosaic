<?php

namespace dhope0000\LXDClient\Tools\Hosts\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;

class HostsHaveInstance
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function ifHostInListHasContainerNameThrow(array $hostIds, string $name)
    {
        foreach ($hostIds as $hostId) {
            $client = $this->client->getANewClient($hostId);
            $allContainers = $client->instances->all();
            if (in_array($name, $allContainers)) {
                throw new \Exception("Already have a container with the name $name", 1);
            }
        }
        return true;
    }
}
