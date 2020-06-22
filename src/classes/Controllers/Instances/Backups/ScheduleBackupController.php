<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Tools\Instances\Backups\AddBackupSchedule;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $addBackupSchedule;

    public function __construct(AddBackupSchedule $addBackupSchedule)
    {
        $this->addBackupSchedule = $addBackupSchedule;
    }
    /**
     * @Route("", name="Set Instance Backup Schedule")
     */
    public function schedule(
        int $userId,
        Host $host,
        string $instance,
        string $frequency,
        string $time,
        int $strategy,
        int $retention
    ) {
        $this->addBackupSchedule->add(
            $userId,
            $host,
            $instance,
            $frequency,
            $time,
            $strategy,
            $retention
        );

        return ["state"=>"success", "message"=>"Set schedule for instance"];
    }
}
