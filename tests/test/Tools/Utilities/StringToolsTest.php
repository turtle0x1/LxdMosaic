<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class StringToolsTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->stringTools = $container->make("dhope0000\LXDClient\Tools\Utilities\StringTools");
    }

    public function testRandomString(): void
    {
        $result = $this->stringTools->random(15);
        $this->assertTrue(strlen((string) $result) == 15);
    }

    public function testStringStartsWith(): void
    {
        $result = $this->stringTools->stringStartsWith('https://wow.com', 'https://');
        $this->assertTrue($result);
    }

    public function testGetStringBetween(): void
    {
        $result = $this->stringTools->getStringBetween('https://wow.com', '//', '.com');
        $this->assertTrue($result === 'wow');

        $result = $this->stringTools->getStringBetween('https://wow.com', 'abc', 'xyz');
        $this->assertTrue($result === '');
    }
}
