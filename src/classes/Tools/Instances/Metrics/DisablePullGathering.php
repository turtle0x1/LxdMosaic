<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Metrics\DeleteMetrics;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Profiles\RemoveProfiles;

class DisablePullGathering
{
    public function __construct(
        private readonly DeleteMetrics $deleteMetrics,
        private readonly RemoveProfiles $removeProfiles
    ) {
    }

    public function disable(Host $host, string $instance, bool $clearData = false)
    {
        if ($clearData) {
            $this->deleteMetrics->deleteForInstance($host->getHostId(), $instance, 'default');
        }

        $this->removeProfiles->remove($host, $instance, ['lxdMosaicPullMetrics']);
        return true;
    }
}
