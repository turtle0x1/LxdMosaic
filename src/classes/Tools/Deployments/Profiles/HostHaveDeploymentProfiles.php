<?php

namespace dhope0000\LXDClient\Tools\Deployments\Profiles;

use dhope0000\LXDClient\Tools\Profiles\GetAllProfiles;

class HostHaveDeploymentProfiles
{
    public function __construct(GetAllProfiles $getAllProfiles)
    {
        $this->getAllProfiles = $getAllProfiles;
    }

    public function getProfileName(int $hostId, int $deploymentId, int $revId)
    {
        $allProfiles = $this->getAllProfiles->getHostProfilesWithDetails($hostId);
        foreach ($allProfiles as $profile => $data) {
            if ($this->profileContainsDeployment($data, $deploymentId, $revId)) {
                return $profile;
            }
        }
        return false;
    }

    public function getAllProfilesInDeployment(int $deploymentId)
    {
        //TODO Broken
        $allProfiles = $this->getAllProfiles->getAllProfiles();
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
