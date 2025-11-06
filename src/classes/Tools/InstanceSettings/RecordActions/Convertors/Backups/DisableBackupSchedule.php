<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings\RecordActions\Convertors\Backups;

use dhope0000\LXDClient\Constants\RecordedActions\Categories;
use dhope0000\LXDClient\Constants\RecordedActions\Methods;
use dhope0000\LXDClient\Interfaces\RecordedActionConvertor;
use dhope0000\LXDClient\Objects\RecordedAction;

class DisableBackupSchedule implements RecordedActionConvertor
{
    #[\Override]
    public function convert(array $recordedAction): RecordedAction
    {
        $title = 'Disable backup schedule for ' . $recordedAction['params']['instance'];

        return new RecordedAction(
            $title,
            new \DateTime($recordedAction['date']),
            Categories::BACKUPS,
            Methods::DISABLE
        );
    }
}
