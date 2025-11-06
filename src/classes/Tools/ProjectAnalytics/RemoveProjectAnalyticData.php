<?php

namespace dhope0000\LXDClient\Tools\ProjectAnalytics;

use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Model\ProjectAnalytics\DeleteAnalytics;

class RemoveProjectAnalyticData
{
    public function __construct(
        private readonly GetSetting $getSetting,
        private readonly InstanceSettingsKeys $instanceSettingsKeys,
        private readonly DeleteAnalytics $deleteAnalytics
    ) {
    }

    public function remove(): void
    {
        $howFarBack = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::PROJECT_ANALYTICS_STORAGE);
        $before = (new \DateTime())->modify($howFarBack);
        $this->deleteAnalytics->deleteBefore($before);
    }
}
