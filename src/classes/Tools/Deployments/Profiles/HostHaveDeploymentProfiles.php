<?php

namespace dhope0000\LXDClient\Tools\Deployments\Profiles;

use dhope0000\LXDClient\Tools\Profiles\GetAllProfiles;
use dhope0000\LXDClient\Objects\Host;

class HostHaveDeploymentProfiles
{
    public function __construct(GetAllProfiles $getAllProfiles)
    {
        $this->getAllProfiles = $getAllProfiles;
    }

    public function getProfileName(Host $host, int $deploymentId, int $revId) :string
    {
        $allProfiles = $host->profiles->all(2);
        foreach ($allProfiles as $profile) {
            if ($this->profileContainsDeployment($profile, $deploymentId, $revId)) {
                return $profile["name"];
            }
        }
        return false;
    }

    public function getAllProfilesInDeployment(int $deploymentId)
    {
        //TODO Broken
        $allProfiles = $this->getAllProfiles->getAllProfiles(true);
        $output = [];
        foreach ($allProfiles["standalone"]["members"] as $host) {
            foreach ($host->getCustomProp("profiles") as $profile) {
                if ($profile["name"] == "default") {
                    continue;
                }
                if ($this->profileContainsDeployment($profile, $deploymentId)) {
                    if (!isset($output[$host->getAlias()])) {
                        $output[$host->getAlias()] = [
                            "hostId"=>$host->getHostId(),
                            "profiles"=>[]
                        ];
                    }
                    $output[$host->getAlias()]["profiles"][] = [
                        "name"=>$profile["name"],
                        "revId"=>$profile["config"]["environment.revId"],
                        "usedBy"=>$profile["used_by"]
                    ];
                }
            }
        }
        return $output;
    }

    private function profileContainsDeployment($profiledata, int $deploymentId, int $revId = null)
    {
        if (!isset($profiledata["config"])) {
            return false;
        }

        $config = $profiledata["config"];

        if (!isset($config["environment.deploymentId"]) && !isset($config["environment.revId"])) {
            return false;
        }

        if ($config["environment.deploymentId"] == $deploymentId) {
            if (is_numeric($revId) && $config["environment.revId"] == $revId) {
                return true;
            } elseif (!is_numeric($revId)) {
                return true;
            }
        }
        return false;
    }
}
