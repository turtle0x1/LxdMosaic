<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\DeployAgentFiles;
use dhope0000\LXDClient\Tools\Instances\Metrics\DeployGenericPullProfile;
use dhope0000\LXDClient\Tools\Profiles\AssignProfiles;
use dhope0000\LXDClient\Objects\Host;

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

    public function enable(Host $host, string $instance)
    {
        $this->deployAgentFiles->deploy($host, $instance);
        $this->deployGenericPullProfile->deploy($host);
        $this->assignProfiles->assign(
            $host,
            $instance,
            ["lxdMosaicPullMetrics"]
        );
        return true;
    }
}
