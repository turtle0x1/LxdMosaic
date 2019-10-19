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
        int $cloudConfigRevId = null,
        array $extraConfigParams = null,
        string $vendorData = null
    ) {
        if (!is_numeric($cloudConfigId) && !is_numeric($cloudConfigRevId)) {
            throw new \Exception("Please provide either cloud config id or rev id", 1);
        } elseif (isset($extraConfigParams["user.user-data"])) {
            throw new \Exception("You can't provide user.user-data here", 1);
        } elseif (isset($extraConfigParams["user.vendor-data"])) {
            throw new \Exception("You can't provide vendor data here", 1);
        }

        $deployResults = [];

        if (!is_numeric($cloudConfigRevId)) {
            $latestData = $this->getConfig->getLatestConfig($cloudConfigId);
        } else {
            $latestData = $this->getConfig->getLatestConfigByRevId($cloudConfigRevId);
        }

        $config = is_array($extraConfigParams) ? $extraConfigParams : [];

        $config = array_merge($config, $latestData["envVariables"] == "" ? [] : json_decode($latestData["envVariables"], true));

        $config["user.user-data"] = $latestData["data"];
        $config["user.vendor-data"] = $vendorData;

        foreach ($hosts as $hostId) {
            $client = $this->client->getANewClient($hostId);
            $serverProfiles = $client->profiles->all();

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
