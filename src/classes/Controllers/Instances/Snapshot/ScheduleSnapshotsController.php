<?php

namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\Snapshot\ScheduleSnapshots;
use Symfony\Component\Routing\Attribute\Route;

class ScheduleSnapshotsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly ScheduleSnapshots $scheduleSnapshots
    ) {
    }

    #[Route(path: '/api/Instances/Snapshot/ScheduleSnapshotsController/schedule', name: 'Schedule instance snapshots with LXD', methods: ['POST'])]
    public function schedule(
        Host $host,
        string $container,
        string $schedule,
        string $pattern,
        string $expiry,
        int $snapshotStopped
    ) {
        $this->scheduleSnapshots->schedule($host, $container, $schedule, $pattern, $expiry, $snapshotStopped);

        return [
            'state' => 'success',
            'message' => "Scheduled snapshots for {$container}",
        ];
    }
}
