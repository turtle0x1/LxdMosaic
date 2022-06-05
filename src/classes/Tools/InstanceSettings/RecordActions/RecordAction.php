<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\InsertActionLog;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

class RecordAction
{
    private $insertActionLog;
    private $getSetting;

    public function __construct(InsertActionLog $insertActionLog, GetSetting $getSetting)
    {
        $this->insertActionLog = $insertActionLog;
        $this->getSetting = $getSetting;
    }

    public function record(int $userId, string $controller, array $params) :void
    {
        if ($this->getSetting->getSettingLatestValue(InstanceSettingsKeys::RECORD_ACTIONS)) {
            $this->insertActionLog->insert($userId, $controller, json_encode($params));
        }
    }
}
