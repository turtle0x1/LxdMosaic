<?php

namespace dhope0000\LXDClient\Tools\Hosts\Backups\Profiles;

use dhope0000\LXDClient\Model\Hosts\Backups\Profiles\Schedules\InsertProfileBackupSchedule;
use dhope0000\LXDClient\Model\Hosts\Backups\Profiles\Schedules\UpdateProfileBackupSchedules;
use dhope0000\LXDClient\Objects\Host;

class AddBackupSchedule
{
    public function __construct(
        InsertProfileBackupSchedule $insertProfileBackupSchedule,
        UpdateProfileBackupSchedules $updateProfileBackupSchedules
    ) {
        $this->insertProfileBackupSchedule = $insertProfileBackupSchedule;
        $this->updateProfileBackupSchedules = $updateProfileBackupSchedules;
    }

    public function add(
        string $userId,
        Host $host,
        string $project,
        string $frequency,
        string $time,
        int $strategyId,
        int $retention,
        array $daysOfWeek = [],
        int $dayOfMonth = 0
    ) {
        $this->validateBakupSchedule($frequency, $time);

        $this->updateProfileBackupSchedules->disableActiveScheds(
            $userId,
            $host->getHostId(),
            $host->callClientMethod("getProject")
        );

        $this->insertProfileBackupSchedule->insert(
            $userId,
            $host->getHostId(),
            $host->callClientMethod("getProject"),
            $frequency . "~ " . $time . "~ [" . implode(",", $daysOfWeek) . "]~ $dayOfMonth~",
            $strategyId,
            $retention
        );
    }

    private function validateBakupSchedule(string $frequency, string $time)
    {
        $allowedSchedules = ["daily", "weekly", "monthly"];
        if (!in_array($frequency, $allowedSchedules)) {
            throw new \Exception("Only supporting daily & weekly backups for the time being", 1);
        }
        // https://stackoverflow.com/a/3964994/4008082
        if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time) == false) {
            throw new \Exception("Time not correct format HH:MM 24 hour format", 1);
        }
    }
}
