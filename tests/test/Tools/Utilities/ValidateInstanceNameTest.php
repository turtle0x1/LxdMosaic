<?php

declare(strict_types=1);

use dhope0000\LXDClient\Tools\Utilities\ValidateInstanceName;
use PHPUnit\Framework\TestCase;

final class ValidateInstanceNameTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
    }

    public function testValidInstanceName()
    {
        $this->assertNull(ValidateInstanceName::validate('instance-01'));
        $this->assertNull(ValidateInstanceName::validate('instance-02'));
        $this->assertNull(ValidateInstanceName::validate('demo-03-instance'));
    }

    public function testToShortInstanceName()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate('');
    }

    public function testToLongInstanceName()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate('Loremipsumdolorsitamet,consecteturadipiscingelit');
    }

    public function testNumericInstanceName()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate('01234');
    }

    public function testStartsWithDigitInstanceName()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate('01-instance');
    }

    public function testStartsWithHypenInstanceName()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate('-instance');
    }

    public function testEndsWithHypenInstanceName()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate('instance-');
    }

    public function testContainsInvalidCharsInstanceName()
    {
        $this->expectException(\Exception::class);
        ValidateInstanceName::validate('instance@@');
    }
}
