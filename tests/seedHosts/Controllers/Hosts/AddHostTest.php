<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AddHostTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->addHosts = $container->make("dhope0000\LXDClient\Controllers\Hosts\AddHostsController");
    }

    public function test_addHosts() :void
    {
        $result = $this->addHosts->add(1, [
            [
                "name"=>"localhost",
                "trustPassword"=>"examplePassword",
                "token"=>null
            ]
        ]);
        $this->assertEquals(["state"=>"success", "messages"=>"Added Hosts"], $result);
    }
}
