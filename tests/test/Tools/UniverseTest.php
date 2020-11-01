<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UniverseTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->universe = $container->make("dhope0000\LXDClient\Tools\Universe");
    }

    public function test_admin_can_access_servers() :void
    {
        $result = $this->universe->getEntitiesUserHasAccesTo(1, null);

        $this->assertArrayHasKey("clusters", $result);
        $this->assertArrayHasKey("standalone", $result);
        $this->assertArrayHasKey("members", $result["standalone"]);
        $this->assertArrayHasKey(0, $result["standalone"]["members"]);
    }

    public function test_user_with_no_access_throws()
    {
        $this->expectException(\Exception::class);
        $result = $this->universe->getEntitiesUserHasAccesTo(999, null);
    }
}
