<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class State
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function change(int $hostId, string $container, string $state)
    {
        $hostClient = $this->lxdClient->getANewClient($hostId);
        return $hostClient->containers->{$state}($container);
    }
}
