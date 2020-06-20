<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/fleetAnalytics.php');
$task
    ->everyFiveMinutes()
    ->description('Gathering fleet metrics');

return $schedule;
