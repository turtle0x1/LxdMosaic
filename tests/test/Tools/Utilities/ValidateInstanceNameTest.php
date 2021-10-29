<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use dhope0000\LXDClient\Tools\Utilities\ValidateInstanceName;

final class ValidateInstanceNameTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
    }

    public function test_valid_instance_name()
    {
        $this->assertNull(ValidateInstanceName::validate("instance-01"));
        $this->assertNull(ValidateInstanceName::validate("instance-02"));
        $this->assertNull(ValidateInstanceName::validate("demo-03-instance"));
    }

    public function test_to_short_instance_name()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate("");
    }

    public function test_to_long_instance_name()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate("Loremipsumdolorsitamet,consecteturadipiscingelit");
    }

    public function test_numeric_instance_Name()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate("01234");
    }

    public function test_starts_with_digit_instance_name()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate("01-instance");
    }

    public function test_starts_with_hypen_instance_name()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate("-instance");
    }

    public function test_ends_with_hypen_instance_name()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate("instance-");
    }

    public function test_contains_invalid_chars_instance_name()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate("instance@@");
    }
}
