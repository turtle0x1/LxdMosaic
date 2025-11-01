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
     * @Route("/api/Instances/Backups/ScheduleBackupController/schedule", name="Set Instance Backup Schedule", methods={"POST"})
     */
    public function schedule(
        int $userId,
        Host $host,
        string $instance,
        string $frequency,
        string $time,
        int $strategy,
        int $retention,
        array $daysOfWeek = [],
        int $dayOfMonth = 0
    ) {
        $this->addBackupSchedule->add(
            $userId,
            $host,
            $instance,
            $frequency,
            $time,
            $strategy,
            $retention,
            $daysOfWeek,
            $dayOfMonth
        );

        return ["state"=>"success", "message"=>"Set schedule for instance"];
    }
}
