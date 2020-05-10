<?php
namespace dhope0000\LXDClient\Tools\Images\Aliases;

use dhope0000\LXDClient\Model\Client\LxdClient;

class UpdateDescription
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function update(int $hostId, string $fingerprint, string $name, string $description = "")
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->images->aliases->replace($name, $fingerprint, $description);
    }
}
