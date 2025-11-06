<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DateToolsTest extends TestCase
{
    private $dateTools;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->dateTools = $container->make("dhope0000\LXDClient\Tools\Utilities\DateTools");
    }

    public function testHoursRange(): void
    {
        $x = $this->dateTools->hoursRange(0, 1, 15);
        $this->assertEquals([
            '00:00' => null,
            '00:15' => null,
            '00:30' => null,
            '00:45' => null,
        ], $x);
    }
}
