<?php

namespace dhope0000\LXDClient\Tools\Backups\Schedule;

use dhope0000\LXDClient\Objects\Backups\BackupSchedule;

class BackupStringToObject
{
    public function trimString($data)
    {
        return trim($data);
    }

    public function convertStringArrayToPhp($string)
    {
        $string = str_replace("[", "", $string);
        $string = str_replace("]", "", $string);
        return array_map([$this, "trimString"], explode(",", $string));
    }


    public function parseString($string) :BackupSchedule
    {
        $this->validateString($string);
        
        $parts = array_map([$this, "trimString"], explode("~", $string));

        $time = $parts[1];

        if (strpos($time, "[") !== false) {
            $time = $this->convertStringArrayToPhp($time);
        } else {
            $time = [$time];
        }

        $daysOfWeek = [];

        if ($parts[2] !== "[]" && !empty($parts[2])) {
            $daysOfWeek = $this->convertStringArrayToPhp($parts[2]);
        }

        return new BackupSchedule(
            $parts[0],
            $time,
            $daysOfWeek,
            is_numeric($parts[3]) ? $parts[3] : 0
        );
    }

    public function validateString(string $string) :void
    {
        if (substr_count($string, "~") < 3) {
            throw new \Exception("Syntax Error: There must be 4 tidles in the string", 1);
        }
    }
}
