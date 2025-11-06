<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Constants\InstanceSettingsKeys;
use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\InsertActionLog;

class RecordAction
{
    public function __construct(
        private readonly InsertActionLog $insertActionLog,
        private readonly GetSetting $getSetting
    ) {
    }

    public function record(int $userId, string $controller, array $params): void
    {
        if ($this->getSetting->getSettingLatestValue(InstanceSettingsKeys::RECORD_ACTIONS)) {
            $this->insertActionLog->insert($userId, $controller, json_encode($params));
        }
    }
}
