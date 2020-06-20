<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run(PHP_BINARY . '  ' . __DIR__ . '/scripts/containerMetrics.php');
$task
    ->everyMinute()
    ->description('Gathering container metrics');

return $schedule;
