<?php

namespace dhope0000\LXDClient\Controllers\Instances\Backups;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Backups\AddBackupSchedule;
use Symfony\Component\Routing\Attribute\Route;

class ScheduleBackupController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly AddBackupSchedule $addBackupSchedule
    ) {
    }

    #[Route(path: '/api/Instances/Backups/ScheduleBackupController/schedule', name: 'Set Instance Backup Schedule', methods: ['POST'])]
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

        return [
            'state' => 'success',
            'message' => 'Set schedule for instance',
        ];
    }
}
