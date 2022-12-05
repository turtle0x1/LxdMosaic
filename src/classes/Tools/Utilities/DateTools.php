<?php

namespace dhope0000\LXDClient\Tools\Utilities;

use dhope0000\LXDClient\Objects\Host;

class DateTools
{
    /**
     * Generate a range of minutes for a day
     * https://stackoverflow.com/a/15887174/4008082
     */
    public function hoursRange(
        int $startHour = 0,
        int $endHour = 24,
        $minuteIncrement = 1,
        string $keyPrefix = '',
        bool $stopAtNow = false,
        int $startMinute = 0
    ) :array {
        $times = array();
        $x = (int) (new \DateTimeImmutable())->format("Hi");
        for ($h = $startHour; $h < $endHour; $h++) {
            $o = $h === $startHour ? $startMinute : 0;
            for ($m = $o; $m < 60 ; $m += $minuteIncrement) {
                if ((int) sprintf('%02d%02d', $h, $m) > $x && $stopAtNow) {
                    break 2;
                }
                $time = sprintf('%02d:%02d', $h, $m);
                $times["{$keyPrefix}{$time}"] = null;
            }
        }
        return $times;
    }

    public function getTimezoneList()
    {
        return timezone_identifiers_list();
    }
}
