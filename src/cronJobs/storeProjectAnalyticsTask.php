<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/storeProjectAnalytics.php');
$task
    ->everyFiveMinutes()
    ->description('Gathering project analytics');

return $schedule;
