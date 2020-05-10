<?php
namespace dhope0000\LXDClient\Tools\Images\Aliases;

use dhope0000\LXDClient\Model\Client\LxdClient;

class RenameAlias
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function rename(int $hostId, string $name, string $newName)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->images->aliases->rename($name, $newName);
    }
}
