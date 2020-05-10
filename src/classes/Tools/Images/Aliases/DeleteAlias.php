<?php
namespace dhope0000\LXDClient\Tools\Images\Aliases;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteAlias
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete(int $hostId, string $name)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->images->aliases->remove($name);
    }
}
