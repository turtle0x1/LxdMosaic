<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UsedByFilterTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->usedByFilter = $container->make("dhope0000\LXDClient\Tools\Utilities\UsedByFilter");

        $getHost = $container->make("dhope0000\LXDClient\Model\Hosts\GetDetails");
        $this->host = $getHost->fetchHost(1);
    }

    public function test_noAccessToDefaultHidesDefaultEntities()
    {
        $result = $this->usedByFilter->filterUserProjects(2, $this->host, [
            "/1.0/instance/c1",
            "/1.0/instance/canSeeMe?project=testProject"
        ]);

        $this->assertEquals([
            "/1.0/instance/canSeeMe?project=testProject",
        ], $result);
    }
}
