<?php

namespace dhope0000\LXDClient\Tools\Containers;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Containers\Settings\GetSettings;

class GetContainerSettings
{
    public function __construct(
        LxdClient $lxdClient,
        GetSettings $getSettings
    ) {
        $this->client = $lxdClient;
        $this->getSettings = $getSettings;
    }

    public function get(string $host, string $container)
    {
        $client = $this->client->getClientByUrl($host);
        $info = $client->containers->info($container);
        $enabledConfigs = $this->getSettings->getAllEnabledNamesAndDefaults();
        $enabledConfigNames = array_column($enabledConfigs, "key");
        $output = [];
        foreach ($info["expanded_config"] as $name => $value) {
            if (in_array($name, $enabledConfigNames)) {
                $key = array_search($name, $enabledConfigNames);
                $output[] = [
                    "key"=>$name,
                    "value"=>$value,
                    "description"=>$enabledConfigs[$key]["description"]
                ];
                unset($enabledConfigs[$key]);
            }
        }
        return [
            "existingSettings"=>$output,
            "remainingSettings"=>$enabledConfigs
        ];
    }
}
