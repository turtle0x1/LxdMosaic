<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;

class StopInstances
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function stop(int $hostId, array $instances)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        foreach ($instances as $instance) {
            $state = $client->instances->state($instance);
            
            if ($state["status_code"] == 102) {
                continue;
            }

            $client->instances->setState($instance, "stop");
        }

        return true;
    }
}
