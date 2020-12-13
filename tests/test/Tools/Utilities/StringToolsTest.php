<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class StringToolsTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->stringTools = $container->make("dhope0000\LXDClient\Tools\Utilities\StringTools");
    }

    public function test_randomString() :void
    {
        $result = $this->stringTools->random(15);
        $this->assertTrue(strlen($result) == 15);
    }

    public function test_stringStartsWith() :void
    {
        $result = $this->stringTools->stringStartsWith("https://wow.com", "https://");
        $this->assertTrue($result);
    }

    public function test_getStringBetween() :void
    {
        $result = $this->stringTools->getStringBetween("https://wow.com", "//", ".com");
        $this->assertTrue($result === "wow");

        $result = $this->stringTools->getStringBetween("https://wow.com", "abc", "xyz");
        $this->assertTrue($result === "");
    }
}
