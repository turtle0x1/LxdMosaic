<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\RecordedActions;

use dhope0000\LXDClient\Model\InstanceSettings\RecordActions\FetchRecordedActions;

class GetLastController
{
    private $fetchRecordedActions;

    public function __construct(FetchRecordedActions  $fetchRecordedActions)
    {
        $this->fetchRecordedActions = $fetchRecordedActions;
    }

    public function get(int $ammount)
    {
        return $this->fetchRecordedActions->fetch($ammount);
    }
}
