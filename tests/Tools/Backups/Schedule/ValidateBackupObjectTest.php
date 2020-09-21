<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Objects\Backups\BackupSchedule;

final class ValidateBackupObjectTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->validateBackupObject = $container->make("dhope0000\LXDClient\Tools\Backups\Schedule\ValidateBackupObject");
    }
    /**
     * @dataProvider validateData
     */
    public function test_validate($schedule, $fails) :void
    {
        if ($fails) {
            $this->expectException(\Exception::class);
        }

        $x = $this->validateBackupObject->validate($schedule);

        if (!$fails) {
            $this->assertNull($x);
        }
    }

    public function validateData()
    {
        return [
            [ // Perfectly fine if not with junk values
                new BackupSchedule(
                    "daily",
                    ["21:00"],
                    [1, 2, 3],
                    22
                ),
                false
            ],
            [ // Wrong backup range
                new BackupSchedule(
                    "wow_this_is_wrong",
                    ["21:00"]
                ),
                true
            ],
            [ // Invalid times
                new BackupSchedule(
                    "daily",
                    ["25:70"]
                ),
                true
            ],
            [ // Invalid days of the week
                new BackupSchedule(
                    "weekly",
                    ["23:00"],
                    [-1, 5]
                ),
                true
            ],
            [ // Invalid days of the month
                new BackupSchedule(
                    "monthly",
                    ["23:00"],
                    [0, 5],
                    200
                ),
                true
            ]
        ];
    }
}
