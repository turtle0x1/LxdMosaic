<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Tools\Profiles\GetAllProfiles;

class GetHostContainerStatus
{
    public function __construct(GetAllProfiles $getAllProfiles)
    {
        $this->getAllProfiles = $getAllProfiles;
    }

    public function get()
    {
        $allProfiles = $this->getAllProfiles->getAllProfiles(true);

        foreach ($allProfiles["clusters"] as $cluster) {
            foreach ($cluster["members"] as &$host) {
                $this->addHostDetails($host);
            }
        }

        foreach ($allProfiles["standalone"]["members"] as &$host) {
            $this->addHostDetails($host);
        }

        return $allProfiles;
    }
    private function addHostDetails(&$host)
    {
        $instances = [];
        $instancesToScan = ["pullMetrics"=>[]];
        foreach ($host->getCustomProp("profiles") as $profile) {
            if (!isset($profile["config"])) {
                continue;
            }

            $config = $profile["config"];

            $pullMetrics = isset($config["environment.lxdMosaicPullMetrics"]);

            if (!$pullMetrics) {
                continue;
            }

            foreach ($profile["used_by"] as $instance) {
                $instance = str_replace("/1.0/instances/", "", $instance);
                $instance = str_replace("/1.0/containers/", "", $instance);

                if (strpos($instance, "?project=default") !== false || strpos($instance, '?project=') === false) {
                    $instance = str_replace("?project=default", "", $instance);
                    $instancesToScan["pullMetrics"][] = $instance;
                }
            }
        }

        $allInstances = $host->instances->all();
        foreach ($allInstances as $instance) {
            $instances[] = [
                "name"=>$instance,
                "pullMetrics"=>in_array($instance, $instancesToScan["pullMetrics"])
            ];
        }

        $host->removeCustomProp("resources");
        $host->removeCustomProp("profiles");
        $host->setCustomProp("instances", $instances);
        return $host;
    }
}
