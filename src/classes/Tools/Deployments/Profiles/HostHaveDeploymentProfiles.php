<?php

namespace dhope0000\LXDClient\Tools\Deployments\Profiles;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\GetAllProfiles;

class HostHaveDeploymentProfiles
{
    public function __construct(
        private readonly GetAllProfiles $getAllProfiles
    ) {
    }

    public function getProfileName(Host $host, int $deploymentId, int $revId): string
    {
        $allProfiles = $host->profiles->all(2);
        foreach ($allProfiles as $profile) {
            if ($this->profileContainsDeployment($profile, $deploymentId, $revId)) {
                return $profile['name'];
            }
        }
        return false;
    }

    public function getAllProfilesInDeployment(int $deploymentId)
    {
        $allProfiles = $this->getAllProfiles->getAllProfiles(true);
        $output = [];
        foreach ($allProfiles['standalone']['members'] as $host) {
            if (!$host->hostOnline()) {
                continue;
            }
            $deployProfiles = [];
            foreach ($host->getCustomProp('profiles') as $profile) {
                if ($profile['name'] == 'default') {
                    continue;
                }
                if ($this->profileContainsDeployment($profile, $deploymentId)) {
                    $deployProfiles[] = [
                        'name' => $profile['name'],
                        'revId' => $profile['config']['environment.revId'],
                        'usedBy' => $profile['used_by'],
                    ];
                }
            }
            $host->setCustomProp('profiles', $deployProfiles);
            $output[] = $host;
        }
        return $output;
    }

    private function profileContainsDeployment($profiledata, int $deploymentId, ?int $revId = null)
    {
        if (!isset($profiledata['config'])) {
            return false;
        }

        $config = $profiledata['config'];

        if (!isset($config['environment.deploymentId']) && !isset($config['environment.revId'])) {
            return false;
        }

        if ($config['environment.deploymentId'] == $deploymentId) {
            if (is_numeric($revId) && $config['environment.revId'] == $revId) {
                return true;
            } elseif (!is_numeric($revId)) {
                return true;
            }
        }
        return false;
    }
}
