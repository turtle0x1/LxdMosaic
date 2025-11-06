<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UniverseTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->universe = $container->make("dhope0000\LXDClient\Tools\Universe");
    }

    public function testAdminCanAccessServers(): void
    {
        $result = $this->universe->getEntitiesUserHasAccesTo(1, null);

        $this->assertArrayHasKey('clusters', $result);
        $this->assertArrayHasKey('standalone', $result);
        $this->assertArrayHasKey('members', $result['standalone']);
        $this->assertArrayHasKey(0, $result['standalone']['members']);
    }

    public function testUserWithNoAccessThrows()
    {
        $this->expectException(\Exception::class);
        $result = $this->universe->getEntitiesUserHasAccesTo(999, null);
    }

    /**
     * checking admin can see the new entity (profiles) inline with permisions.
     * (the fact its "profiles" is irrelevant)
     */
    public function testAdminCanSeeNewEntity()
    {
        $r = $this->universe->getEntitiesUserHasAccesTo(1, 'profiles');
        $result = $r['standalone']['members'][0]->getCustomProp('profiles');

        $this->assertEquals(['default', 'testProfile'], $result);
    }

    /**
     * checking "test" cant see the new entity (profiles) inline with permisions
     */
    public function testUserTwoCantSeeNewEntity()
    {
        $r = $this->universe->getEntitiesUserHasAccesTo(2, 'profiles');
        $result = $r['standalone']['members'][0]->getCustomProp('profiles');
        $this->assertEquals(['default'], $result);
    }
}
