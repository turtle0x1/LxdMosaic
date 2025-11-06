<?php

declare(strict_types=1);

use dhope0000\LXDClient\Objects\Backups\BackupSchedule;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class ValidateBackupObjectTest extends TestCase
{
    private $validateBackupObject;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->validateBackupObject = $container->make(
            "dhope0000\LXDClient\Tools\Backups\Schedule\ValidateBackupObject"
        );
    }

    #[DataProvider('validateData')]
    public function testValidate($schedule, $fails): void
    {
        if ($fails) {
            $this->expectException(\Exception::class);
        }

        $x = $this->validateBackupObject->validate($schedule);

        if (!$fails) {
            $this->assertNull($x);
        }
    }

    public static function validateData()
    {
        return [
            [ // Perfectly fine if not with junk values
                new BackupSchedule('daily', ['21:00'], [1, 2, 3], 22),
                false,
            ],
            [ // Wrong backup range
                new BackupSchedule('wow_this_is_wrong', ['21:00']),
                true,
            ],
            [ // Invalid times
                new BackupSchedule('daily', ['25:70']),
                true,
            ],
            [ // Invalid days of the week
                new BackupSchedule('weekly', ['23:00'], [-1, 5]),
                true,
            ],
            [ // Invalid days of the month
                new BackupSchedule('monthly', ['23:00'], [0, 5], 200),
                true,
            ],
        ];
    }
}
