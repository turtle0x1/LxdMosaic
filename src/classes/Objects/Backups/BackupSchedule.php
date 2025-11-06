<?php

declare(strict_types=1);

namespace dhope0000\LXDClient\Objects\Backups;

class BackupSchedule
{
    public function __construct(
        private readonly string $range,
        private readonly array $times,
        private readonly array $daysOfWeek = [],
        private readonly int $dayOfMonth = 0
    ) {
    }

    public function getRange(): string
    {
        return $this->range;
    }

    public function getTimes(): array
    {
        return $this->times;
    }

    public function getDaysOfWeek(): array
    {
        return $this->daysOfWeek;
    }

    public function getDayOfMonth(): int
    {
        return $this->dayOfMonth;
    }
}
