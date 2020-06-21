<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/enforceBackupRetention.php');
$task
    ->daily()
    ->at("02:00")
    ->description('Check Retention');

return $schedule;
