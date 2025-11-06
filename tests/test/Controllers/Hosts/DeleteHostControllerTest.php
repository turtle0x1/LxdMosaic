<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class DeleteHostControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNonAdminUserCantDeleteHost(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'hostId' => 1,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/Hosts/DeleteHostController/delete', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );

        $this->assertEquals($expected, $result);
    }
}
