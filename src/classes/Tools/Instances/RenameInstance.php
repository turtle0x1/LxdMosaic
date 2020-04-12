<?php
namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RenameInstance
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function rename(int $hostId, string $instance, string $newInstance)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->instances->rename($instance, $newInstance, true);
    }
}
