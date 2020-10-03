<?php

namespace dhope0000\LXDClient\Tools\Backups\Schedule;

use dhope0000\LXDClient\Objects\Backups\BackupSchedule;

class ValidateBackupObject
{
    public function validate(BackupSchedule $schedule)
    {
        $this->validateRange($schedule);
        $this->validateTimes($schedule);
        $this->validateDaysOfWeek($schedule);
        $this->validateDayOfMonth($schedule);
    }


    public function validateRange(BackupSchedule $schedule)
    {
        $allowedRange = ["daily", "weekly", "monthly"];
        if (!in_array($schedule->getRange(), $allowedRange)) {
            throw new \Exception("Unknown Rnage: The range is unknown", 1);
        }
    }

    public function validateTimes(BackupSchedule $schedule)
    {
        $times = $schedule->getTimes();
        foreach ($times as $time) {
            $x = preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $time);
            if ($x === 0) {
                throw new \Exception("Invalid time in schedule: $time", 1);
            }
        }
    }

    public function validateDaysOfWeek(BackupSchedule $schedule)
    {
        $daysOfWeek = $schedule->getDaysOfWeek();
        foreach ($daysOfWeek as $day) {
            if (!is_numeric($day) || ($day < 0 || $day > 6)) {
                throw new \Exception("Invalid time in schedule: $day", 1);
            }
        }
    }

    public function validateDayOfMonth(BackupSchedule $schedule)
    {
        $dayOfMonth = $schedule->getDayOfMonth();

        if (!is_numeric($dayOfMonth) || ($dayOfMonth < 0 || $dayOfMonth > 31)) {
            throw new \Exception("Invalid day of month: $dayOfMonth", 1);
        }
    }
}
