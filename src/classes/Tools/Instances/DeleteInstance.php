<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteInstance
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, string $instance)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->instances->remove($instance);
    }
}
