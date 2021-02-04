<?php

namespace dhope0000\LXDClient\Tools\ProjectAnalytics;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\ProjectAnalytics\DeleteAnalytics;

class RemoveProjectAnalyticData
{
    private $getSetting;
    private $instanceSettingsKeys;
    private $deleteAnalytics;

    public function __construct(
        GetSetting $getSetting,
        InstanceSettingsKeys $instanceSettingsKeys,
        DeleteAnalytics $deleteAnalytics
    ) {
        $this->getSetting = $getSetting;
        $this->instanceSettingsKeys = $instanceSettingsKeys;
        $this->deleteAnalytics = $deleteAnalytics;
    }

    public function remove() :void
    {
        $howFarBack = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::PROJECT_ANALYTICS_STORAGE);
        $before = (new \DateTime())->modify($howFarBack);
        $this->deleteAnalytics->deleteBefore($before);
    }
}
