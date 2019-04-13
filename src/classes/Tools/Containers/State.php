<?php
namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class State
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function change($host, $container, $state)
    {
        //TODO Check the destination is aviable for us to manage
        $hostClient = $this->lxdClient->getClientByUrl($host);
        return $hostClient->containers->{$state}($container);
    }
}
