<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;

class AssignProfiles
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function assign(
        int $hostId,
        string $instance,
        array $profiles
    ) {
        $client = $this->client->getANewClient($hostId);


        $info = $client->instances->info($instance);
        $info["profiles"] = array_merge($profiles, $info["profiles"]);

        if(empty($info["devices"])){
            unset($info["devices"]);
        }

        $client->instances->replace($instance, $info);
    }
}
