<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetUserAllowedProjectsControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    public function testNonAdminTryingToGetUserAllowedProjects(): void
    {
        $this->expectException(\Exception::class);

        $_POST = [
            'targetUser' => 1,
        ];

        $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/GetUserAllowedProjectsController/get', 'POST'),
            [
                'userid' => 2,
            ],
            true
        );
    }

    public function testAdminGettingAdminAllowedProjects(): void
    {
        $_POST = [
            'targetUser' => 1,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/GetUserAllowedProjectsController/get', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals([
            'isAdmin' => true,
            'projects' => [],
        ], $result);
    }

    public function testAdminGettingNonAdminAllowedProjects(): void
    {
        $_POST = [
            'targetUser' => 2,
        ];

        $result = $this->routeApi->route(
            Request::create('/api/User/AllowedProjects/GetUserAllowedProjectsController/get', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertTrue($result['isAdmin'] == false);
        $this->assertTrue(count($result['projects']) > 0);
        $this->assertEquals($result['projects'][0]->getCustomProp('projects'), ['testProject']);
    }
}
