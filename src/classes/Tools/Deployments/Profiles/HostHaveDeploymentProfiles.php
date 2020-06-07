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
        foreach ($allProfiles["standalone"]["members"] as $host => $data) {
            foreach ($data["profiles"] as $profile) {
                if ($profile["details"]["name"] == "default") {
                    continue;
                }
                if ($this->profileContainsDeployment($profile, $deploymentId)) {
                    if (!isset($output[$host])) {
                        $output[$host] = [
                            "hostId"=>$data["hostId"],
                            "profiles"=>[]
                        ];
                    }
                    $output[$host]["profiles"][] = [
                        "name"=>$profile["details"]["name"],
                        "revId"=>$profile["details"]["config"]["environment.revId"],
                        "usedBy"=>$profile["details"]["used_by"]
                    ];
                }
            }
        }
        return $output;
    }

    private function profileContainsDeployment($profiledata, int $deploymentId, int $revId = null)
    {
        $data = $profiledata["details"];

        if (!isset($data["config"])) {
            return false;
        }

        $config = $data["config"];

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
