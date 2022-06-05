<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/removeDeadBackupSchedules.php');
$task
    ->hourly()
    ->description('Check for instances that may have been deleted but schedules remain');

return $schedule;
