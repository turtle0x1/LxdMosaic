<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\InsertActionLog;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

class RecordAction
{
    private $insertActionLog;

    public function __construct(InsertActionLog $insertActionLog, GetSetting $getSetting)
    {
        $this->insertActionLog = $insertActionLog;
        $this->getSetting = $getSetting;
    }

    public function record(string $controller, array $params) :void
    {
        if ($this->getSetting->getSettingLatestValue(InstanceSettingsKeys::RECORD_ACTIONS)) {
            $this->insertActionLog->insert($controller, json_encode($params));
        }
    }
}
