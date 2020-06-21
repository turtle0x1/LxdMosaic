<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\AddBackupSchedule;
use dhope0000\LXDClient\Objects\Host;

class ScheduleBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $addBackupSchedule;

    public function __construct(AddBackupSchedule $addBackupSchedule)
    {
        $this->addBackupSchedule = $addBackupSchedule;
    }

    public function schedule(
        int $userId,
        Host $host,
        string $instance,
        string $frequency,
        string $time,
        int $strategy
    ) {
        $this->addBackupSchedule->add(
            $userId,
            $host,
            $instance,
            $frequency,
            $time,
            $strategy
        );

        return ["state"=>"success", "message"=>"Set schedule for instance"];
    }
}
