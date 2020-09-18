<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\InsertBackupSchedule;
use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\UpdateBackupSchedules;
use dhope0000\LXDClient\Objects\Host;

class AddBackupSchedule
{
    public function __construct(
        InsertBackupSchedule $insertBackupSchedule,
        UpdateBackupSchedules $updateBackupSchedules
    ) {
        $this->insertBackupSchedule = $insertBackupSchedule;
        $this->updateBackupSchedules = $updateBackupSchedules;
    }

    public function add(
        string $userId,
        Host $host,
        string $instance,
        string $frequency,
        string $time,
        int $strategyId,
        int $retention,
        array $daysOfWeek = []
    ) {
        $this->validateBakupSchedule($frequency, $time);

        $this->updateBackupSchedules->disableActiveScheds(
            $userId,
            $host->getHostId(),
            $instance,
            "default"
        );

        $this->insertBackupSchedule->insert(
            $userId,
            $host->getHostId(),
            $instance,
            "default",
            $frequency . "~ " . $time . "~ [" . implode(",", $daysOfWeek) . "]~",
            $strategyId,
            $retention
        );
    }

    private function validateBakupSchedule(string $frequency, string $time)
    {
        if ($frequency !== "daily" && $frequency !== "weekly") {
            throw new \Exception("Only supporting daily & weekly backups for the time being", 1);
        }
        // https://stackoverflow.com/a/3964994/4008082
        if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time) == false) {
            throw new \Exception("Time not correct format HH:MM 24 hour format", 1);
        }
    }
}
