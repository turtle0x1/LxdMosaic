<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Model\Instances\Settings\GetSettings;

class GetInstanceSettings
{
    public function __construct(GetSettings $getSettings)
    {
        $this->getSettings = $getSettings;
    }

    public function get(Host $host, string $instance)
    {
        $info = $host->instances->info($instance);
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
