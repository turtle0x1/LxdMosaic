<?php

namespace dhope0000\LXDClient\Tools\Instances\Backups;

use dhope0000\LXDClient\Model\Hosts\Backups\Instances\Schedules\InsertBackupSchedule;
use dhope0000\LXDClient\Objects\Host;

class AddBackupSchedule
{
    public function __construct(
        InsertBackupSchedule $insertBackupSchedule
    ) {
        $this->insertBackupSchedule = $insertBackupSchedule;
    }

    public function add(
        string $userId,
        Host $host,
        string $instance,
        string $frequency,
        string $time,
        int $strategyId
    ) {
        $this->validateBakupSchedule($frequency, $time);
        $this->insertBackupSchedule->insert(
            $userId,
            $host->getHostId(),
            $instance,
            "default",
            $frequency . " " . $time,
            $strategyId
        );
    }

    private function validateBakupSchedule(string $frequency, string $time)
    {
        if ($frequency !== "daily") {
            throw new \Exception("Only supporting daily backups for the time being", 1);
        }
        // https://stackoverflow.com/a/3964994/4008082
        if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time) == false) {
            throw new \Exception("Time not correct format HH:MM 24 hour format", 1);
        }
    }
}
