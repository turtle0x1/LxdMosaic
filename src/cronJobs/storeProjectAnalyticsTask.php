<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/storeProjectAnalytics.php');
$task
    ->cron("*/5 * * * * ")
    ->description('Gathering project analytics');

return $schedule;
