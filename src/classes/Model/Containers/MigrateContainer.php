<?php
namespace dhope0000\LXDClient\Model\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;

class MigrateContainer
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function migrate($host, $container, $destination, $newContainerName)
    {
        //TODO Check the destination is aviable for us to manage
        $hostClient = $this->lxdClient->getClientByUrl($host);
        $destinationClient = $this->lxdClient->getClientByUrl($destination);
        $hostClient->containers->migrate($destinationClient, $container, $newContainerName, true);
        return true;
    }
}
