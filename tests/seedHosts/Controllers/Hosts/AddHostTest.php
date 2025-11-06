<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AddHostTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->addHosts = $container->make("dhope0000\LXDClient\Controllers\Hosts\AddHostsController");
    }

    public function testAddHosts(): void
    {
        $result = $this->addHosts->add(1, [
            [
                'name' => 'localhost',
                'trustPassword' => 'examplePassword',
                'token' => null,
            ],
        ]);
        $this->assertEquals([
            'state' => 'success',
            'messages' => 'Added Hosts',
        ], $result);
    }
}
