<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/averageInstanceMetrics.php');
$task
    ->everyHour()
    ->description('Averaging instance metrics');

return $schedule;
