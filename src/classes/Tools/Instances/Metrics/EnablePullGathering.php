<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\AssignProfiles;

class EnablePullGathering
{
    public function __construct(
        private readonly DeployGenericPullProfile $deployGenericPullProfile,
        private readonly AssignProfiles $assignProfiles
    ) {
    }

    public function enable(Host $host, string $instance)
    {
        $this->deployGenericPullProfile->deploy($host);
        $this->assignProfiles->assign($host, $instance, ['lxdMosaicPullMetrics']);
        return true;
    }
}
