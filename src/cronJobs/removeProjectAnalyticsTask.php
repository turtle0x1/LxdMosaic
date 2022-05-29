<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/removeProjectAnalytics.php');
$task
    ->cron("*/6 * * * * ")
    ->description('Removing project analytics');

return $schedule;
