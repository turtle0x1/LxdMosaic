<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Tools\Instances\Metrics\DeployGenericPullProfile;
use dhope0000\LXDClient\Tools\Profiles\AssignProfiles;
use dhope0000\LXDClient\Objects\Host;

class EnablePullGathering
{
    private $deployGenericPullProfile;
    private $assignProfiles;
    
    public function __construct(
        DeployGenericPullProfile $deployGenericPullProfile,
        AssignProfiles $assignProfiles
    ) {
        $this->deployGenericPullProfile = $deployGenericPullProfile;
        $this->assignProfiles = $assignProfiles;
    }

    public function enable(Host $host, string $instance)
    {
        $this->deployGenericPullProfile->deploy($host);
        $this->assignProfiles->assign(
            $host,
            $instance,
            ["lxdMosaicPullMetrics"]
        );
        return true;
    }
}
