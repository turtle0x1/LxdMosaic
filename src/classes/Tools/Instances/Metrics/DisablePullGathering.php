<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Tools\Profiles\RemoveProfiles;
use dhope0000\LXDClient\Objects\Host;

class DisablePullGathering
{
    public function __construct(RemoveProfiles $removeProfiles)
    {
        $this->removeProfiles = $removeProfiles;
    }

    public function disable(Host $host, string $instance)
    {
        $this->removeProfiles->remove($host, $instance, ["lxdMosaicPullMetrics"]);
        return true;
    }
}
