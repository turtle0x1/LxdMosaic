<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\Metrics\DeleteMetrics;

class RemoveInstanceMetricHistory
{
    private $getSetting;
    private $deleteMetrics;

    public function __construct(
        GetSetting $getSetting,
        DeleteMetrics $deleteMetrics
    ) {
        $this->getSetting = $getSetting;
        $this->deleteMetrics = $deleteMetrics;
    }

    public function remove()
    {
        $howFarBack = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::INSTANCE_METRIC_HISTORY);
        $before = (new \DateTime())->modify($howFarBack);
        $this->deleteMetrics->deleteBefore($before);
    }
}
