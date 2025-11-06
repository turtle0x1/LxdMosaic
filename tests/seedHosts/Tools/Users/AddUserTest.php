<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AddUserTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->addUser = $container->make("dhope0000\LXDClient\Tools\User\AddUser");
    }

    /**
     * Test used to create user, thats given permisons later on
     */
    public function testCreateNewUser(): void
    {
        $this->assertEquals(true, $this->addUser->add(1, 'testUser', 'Test1234'));
        $this->assertEquals(true, $this->addUser->add(1, 'testUserNoAccess', 'Test1234'));
    }

    public function testDuplicateUsernameThrows(): void
    {
        $this->expectException(\Exception::class);
        $this->addUser->add(1, 'admin', 'test123');
    }
}
