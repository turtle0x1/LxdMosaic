<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetStrategiesTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->getStrategies = $container->make(
            "dhope0000\LXDClient\Controllers\Backups\Strategies\GetStrategiesController"
        );
    }

    public function testGetStrategies(): void
    {
        $result = $this->getStrategies->get();
        $this->assertEquals([
            [
                'id' => '1',
                'name' => 'Backup & Import',
            ],
        ], $result);
    }
}
