<?php
namespace dhope0000\LXDClient\Model\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteProfile
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function delete(
        string $host,
        string $profile
    ) {
        $client = $this->client->getClientByUrl($host);
        return $client->profiles->remove($profile);
    }
}
