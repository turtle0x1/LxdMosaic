<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Metrics\DeleteMetrics;
use dhope0000\LXDClient\Tools\Profiles\RemoveProfiles;
use dhope0000\LXDClient\Objects\Host;

class DisablePullGathering
{
    private DeleteMetrics $deleteMetrics;
    private RemoveProfiles $removeProfiles;

    public function __construct(DeleteMetrics $deleteMetrics, RemoveProfiles $removeProfiles)
    {
        $this->deleteMetrics = $deleteMetrics;
        $this->removeProfiles = $removeProfiles;
    }

    public function disable(Host $host, string $instance, bool $clearData = false) :bool
    {
        if ($clearData) {
            $this->deleteMetrics->deleteForInstance($host->getHostId(), $instance, "default");
        }

        $this->removeProfiles->remove($host, $instance, ["lxdMosaicPullMetrics"]);
        return true;
    }
}
