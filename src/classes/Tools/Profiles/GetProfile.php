<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;

class GetProfile
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function get(int $hostId, string $profile)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        return $client->profiles->info($profile);
    }
}
