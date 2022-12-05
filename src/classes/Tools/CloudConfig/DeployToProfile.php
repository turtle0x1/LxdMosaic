<?php

namespace dhope0000\LXDClient\Tools\CloudConfig;

use dhope0000\LXDClient\Model\CloudConfig\GetConfig;
use dhope0000\LXDClient\Objects\HostsCollection;

class DeployToProfile
{
    private GetConfig $getConfig;

    public function __construct(GetConfig $getConfig)
    {
        $this->getConfig = $getConfig;
    }

    public function getConfig(
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


        if (!is_numeric($cloudConfigRevId)) {
            $latestData = $this->getConfig->getLatestConfig($cloudConfigId);
        } else {
            $latestData = $this->getConfig->getLatestConfigByRevId($cloudConfigRevId);
        }

        $config = is_array($extraConfigParams) ? $extraConfigParams : [];

        $config = array_merge($config, $latestData["envVariables"] == "" ? [] : json_decode($latestData["envVariables"], true));

        $config["user.user-data"] = $latestData["data"];

        if ($vendorData !== null) {
            $config["user.vendor-data"] = $vendorData;
        }

        return $config;
    }

    public function deployToHosts(
        string $profileName,
        HostsCollection $hosts,
        int $cloudConfigId = null,
        int $cloudConfigRevId = null,
        array $extraConfigParams = null,
        string $vendorData = null
    ) {
        $config =  $this->getConfig(
            $cloudConfigId,
            $cloudConfigRevId,
            $extraConfigParams,
            $vendorData
        );
        $deployResults = [];
        foreach ($hosts as $host) {
            $serverProfiles = $host->profiles->all();

            $function = "create";

            if (in_array($profileName, $serverProfiles)) {
                $function = "replace";
            }

            $description = "";

            $deployResults[] = $host->profiles->$function(
                $profileName,
                $description,
                $config
            );
        }
        return $deployResults;
    }
}
