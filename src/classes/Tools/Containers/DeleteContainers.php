<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteContainers
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, array $containers)
    {
        $client = $this->lxdClient->getANewClient($hostId);

        foreach ($containers as $container) {
            $state = $client->instances->state($container);

            if ($state["status_code"] == 103) {
                $client->instances->setState($container, "stop");
            }

            $client->instances->remove($container, true);
        }

        return true;
    }
}
