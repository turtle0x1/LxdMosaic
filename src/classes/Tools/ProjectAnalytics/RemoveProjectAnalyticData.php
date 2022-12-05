<?php

namespace dhope0000\LXDClient\Tools\ProjectAnalytics;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\ProjectAnalytics\DeleteAnalytics;

class RemoveProjectAnalyticData
{
    private GetSetting $getSetting;
    private DeleteAnalytics $deleteAnalytics;

    public function __construct(
        GetSetting $getSetting,
        DeleteAnalytics $deleteAnalytics
    ) {
        $this->getSetting = $getSetting;
        $this->deleteAnalytics = $deleteAnalytics;
    }

    public function remove() :void
    {
        $howFarBack = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::PROJECT_ANALYTICS_STORAGE);
        $before = (new \DateTime())->modify($howFarBack);
        $this->deleteAnalytics->deleteBefore($before);
    }
}
