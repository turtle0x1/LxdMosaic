<?php
namespace dhope0000\LXDClient\Controllers\Instances\Snapshot;

use dhope0000\LXDClient\Tools\Instances\Snapshot\ScheduleSnapshots;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleSnapshotsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $scheduleSnapshots;

    public function __construct(ScheduleSnapshots $scheduleSnapshots)
    {
        $this->scheduleSnapshots = $scheduleSnapshots;
    }
    /**
     * @Route("", name="Schedule instance snapshots with LXD")
     */
    public function schedule(
        Host $host,
        string $container,
        string $schedule,
        string $pattern,
        string $expiry,
        int $snapshotStopped
    ) {
        $this->scheduleSnapshots->schedule(
            $host,
            $container,
            $schedule,
            $pattern,
            $expiry,
            $snapshotStopped
        );

        return ["state"=>"success", "message"=>"Scheduled snapshots for $container"];
    }
}
