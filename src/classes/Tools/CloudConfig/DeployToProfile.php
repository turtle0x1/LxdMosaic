<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\CloudConfig\GetConfig;

class DeployToProfile
{
    public function __construct(
        LxdClient $lxdClient,
        GetConfig $getConfig
    ) {
        $this->client = $lxdClient;
        $this->getConfig = $getConfig;
    }

    public function deployToHosts(
        string $profileName,
        array $hosts,
        int $cloudConfigId
    ) {
        $deployResults = [];
        foreach ($hosts as $host) {
            $client = $this->client->getClientByUrl($host);
            $serverProfiles = $client->profiles->all();

            $latestData = $this->getConfig->getLatestConfig($cloudConfigId);

            $config = [];

            $config["user.user-data"] = $latestData["data"];

            $function = "create";

            if (in_array($profileName, $serverProfiles)) {
                $function = "replace";
            }

            $description = "";

            $deployResults[] = $client->profiles->$function(
                $profileName,
                $description,
                $config
            );
        }
        return $deployResults;
    }
}
