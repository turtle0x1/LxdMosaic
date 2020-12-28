<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DateToolsTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->dateTools = $container->make("dhope0000\LXDClient\Tools\Utilities\DateTools");
    }

    public function test_hoursRange() :void
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
