<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\DeployAgentFiles;
use dhope0000\LXDClient\Tools\Instances\Metrics\DeployGenericPullProfile;
use dhope0000\LXDClient\Tools\Profiles\AssignProfiles;

class EnablePullGathering
{
    public function __construct(
        DeployAgentFiles $deployAgentFiles,
        DeployGenericPullProfile $deployGenericPullProfile,
        AssignProfiles $assignProfiles
    ) {
        $this->deployAgentFiles = $deployAgentFiles;
        $this->deployGenericPullProfile = $deployGenericPullProfile;
        $this->assignProfiles = $assignProfiles;
    }

    public function enable(int $hostId, string $instance)
    {
        $this->deployAgentFiles->deploy($hostId, $instance);
        $this->deployGenericPullProfile->deploy($hostId);
        $this->assignProfiles->assign(
            $hostId,
            $instance,
            ["lxdMosaicPullMetrics"]
        );
        return true;
    }

}
