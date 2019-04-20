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
        int $cloudConfigId = null,
        int $cloudConfigRevId = null
    ) {
        if(!is_numeric($cloudConfigId) && !is_numeric($cloudConfigRevId)){
            throw new \Exception("Please provide either cloud config id or rev id", 1);
        }

        $deployResults = [];

        if(!is_numeric($cloudConfigRevId)){
            $latestData = $this->getConfig->getLatestConfig($cloudConfigId);
        } else {
            $latestData = $this->getConfig->getLatestConfigByRevId($cloudConfigRevId);
        }

        foreach ($hosts as $hostId) {
            $client = $this->client->getANewClient($hostId);
            $serverProfiles = $client->profiles->all();

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
