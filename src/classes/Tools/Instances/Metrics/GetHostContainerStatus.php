<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Tools\Profiles\GetAllProfiles;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;

class GetHostContainerStatus
{
    private GetAllProfiles $getAllProfiles;

    public function __construct(GetAllProfiles $getAllProfiles)
    {
        $this->getAllProfiles = $getAllProfiles;
    }

    public function get() :array
    {
        $allProfiles = $this->getAllProfiles->getAllProfiles(true);

        foreach ($allProfiles["clusters"] as $cluster) {
            foreach ($cluster["members"] as &$host) {
                if (!$host->hostOnline()) {
                    continue;
                }
                $this->addHostDetails($host);
            }
        }

        foreach ($allProfiles["standalone"]["members"] as &$host) {
            if (!$host->hostOnline()) {
                continue;
            }
            $this->addHostDetails($host);
        }

        return $allProfiles;
    }
    private function addHostDetails(&$host)
    {
        $instancesToScan = ["pullMetrics"=>[]];
        //TODO Should probably check that the host supports this extension
        //     but how old is that host (Wish the LXD docs were clearer)?
        $instances = $host->instances->all(LxdRecursionLevels::INSTANCE_HALF_RECURSION);

        foreach ($instances as $index => $instance) {
            $instances[$instance["name"]] = $instance;
            unset($instances[$index]);
        }

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
                    $hostLocation = $instances[$instance]["location"];
                    if ($hostLocation !== "none" && $hostLocation !== "" && $hostLocation !== $host->getAlias()) {
                        continue;
                    }
                    $instance = str_replace("?project=default", "", $instance);
                    $instancesToScan["pullMetrics"][] = $instance;
                }
            }
        }

        $instances = [];

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
