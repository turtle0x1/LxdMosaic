<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\RecordedActions;

use dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\GetActions;

class GetLastController
{
    private $fetchRecordedActions;

    public function __construct(GetActions $getActions)
    {
        $this->getActions = $getActions;
    }

    public function get(int $userId, int $ammount)
    {
        return $this->getActions->get($userId, $ammount);
    }
}
