<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Backups;

use dhope0000\LXDClient\Constants\RecordedActions\Categories;
use dhope0000\LXDClient\Constants\RecordedActions\Methods;
use dhope0000\LXDClient\Interfaces\RecordedActionConvertor;
use dhope0000\LXDClient\Objects\RecordedAction;

class ScheduleBackup implements RecordedActionConvertor
{
    #[\Override]
    public function convert(array $recordedAction): RecordedAction
    {
        $title = 'Created backup schedule for ' . $recordedAction['params']['instance'] .
            " {$recordedAction['params']['frequency']}";

        return new RecordedAction(
            $title,
            new \DateTime($recordedAction['date']),
            Categories::BACKUPS,
            Methods::SCHEDULE
        );
    }
}
