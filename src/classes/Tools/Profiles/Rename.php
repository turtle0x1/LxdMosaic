<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;

class Rename
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function rename(
        string $host,
        string $currentName,
        string $newProfileName
    ) {
        $client = $this->client->getClientByUrl($host);
        return $client->profiles->rename($currentName, $newProfileName);
    }
}
