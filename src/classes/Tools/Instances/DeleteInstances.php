<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteInstances
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, array $instances)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        foreach ($instances as $instance) {
            $state = $client->instances->state($instance);

            if ($state["status_code"] == 103) {
                $client->instances->setState($instance, "stop");
            }

            $client->instances->remove($instance, true);
        }

        return true;
    }
}
