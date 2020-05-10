<?php
namespace dhope0000\LXDClient\Tools\Images\Aliases;

use dhope0000\LXDClient\Model\Client\LxdClient;

class CreateAlias
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function create(int $hostId, string $fingerprint, string $name, string $description = "")
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->images->aliases->create($fingerprint, $name, $description);
    }
}
