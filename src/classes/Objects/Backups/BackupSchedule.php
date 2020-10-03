<?php declare(strict_types=1);

namespace dhope0000\LXDClient\Objects\Backups;

class BackupSchedule
{
    private $range;

    private $times;

    private $daysOfWeek;

    private $dayOfMonth;

    public function __construct(
        string $range,
        array $times,
        array $daysOfWeek = [],
        int $dayOfMonth = 0
    ) {
        $this->range = $range;
        $this->times = $times;
        $this->daysOfWeek  = $daysOfWeek;
        $this->dayOfMonth = $dayOfMonth;
    }

    public function getRange() :string
    {
        return $this->range;
    }

    public function getTimes() :array
    {
        return $this->times;
    }

    public function getDaysOfWeek() :array
    {
        return $this->daysOfWeek;
    }

    public function getDayOfMonth() :int
    {
        return $this->dayOfMonth;
    }
}
