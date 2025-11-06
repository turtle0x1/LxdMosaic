<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GrantAccessTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->grantAccess = $container->make("dhope0000\LXDClient\Tools\User\AllowedProjects\GrantAccess");
    }

    /**
     * Test used to give permisons that are relied on later tests
     */
    public function testGrantUserTwoPermision(): void
    {
        $this->assertEquals(true, $this->grantAccess->grant(1, 2, [1], ['testProject']));
        // Run it twice and it will check the skipping code to prevent duplicates
        $this->assertEquals(true, $this->grantAccess->grant(1, 2, [1], ['testProject']));
    }

    public function testGrantingAdminThrows(): void
    {
        $this->expectException(\Exception::class);
        $this->grantAccess->grant(1, 1, [1], ['testProject']);
    }

    public function testNoAdminCantGrant(): void
    {
        $this->expectException(\Exception::class);
        $this->grantAccess->grant(2, 1, [1], ['testProject']);
    }
}
