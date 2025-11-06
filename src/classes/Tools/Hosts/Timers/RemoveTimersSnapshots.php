<?php

namespace dhope0000\LXDClient\Tools\Hosts\Timers;

use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\Hosts\Timers\DeleteTimerSnapshots as DeleteFromDB;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;

class RemoveTimersSnapshots
{
    public function __construct(
        private readonly GetSetting $getSetting,
        private readonly DeleteFromDB $deleteFromDB
    ) {
    }

    public function remove(): void
    {
        $howFarBack = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::TIMERS_MONITOR_DAYS_DURATION);
        $before = (new \DateTime())->modify("-{$howFarBack} days");
        $this->deleteFromDB->deleteBefore($before);
    }
}
