<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions;

use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\InsertActionLog;

class RecordAction
{
    private $insertActionLog;

    public function __construct(InsertActionLog $insertActionLog)
    {
        $this->insertActionLog = $insertActionLog;
    }

    public function record(string $controller, array $params)
    {
        $this->insertActionLog->insert($controller, json_encode($params));
    }
}
