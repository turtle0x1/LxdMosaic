<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteProfile
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function delete(
        int $hostId,
        string $profile
    ) {
        $client = $this->client->getANewClient($hostId);
        return $client->profiles->remove($profile);
    }
}
