<?php

namespace dhope0000\LXDClient\Tools\Hosts\Timers;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\Hosts\Timers\DeleteTimerSnapshots as DeleteFromDB;

class RemoveTimersSnapshots
{
    private $getSetting;
    private $deleteFromDB;

    public function __construct(
        GetSetting $getSetting,
        DeleteFromDB $deleteFromDB
    ) {
        $this->getSetting = $getSetting;
        $this->deleteFromDB = $deleteFromDB;
    }

    public function remove() :void
    {
        $howFarBack = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::TIMERS_MONITOR_DAYS_DURATION);
        $before = (new \DateTime())->modify("-$howFarBack days");
        $this->deleteFromDB->deleteBefore($before);
    }
}
