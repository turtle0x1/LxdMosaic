<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\Attributes\DataProvider;

final class GetProfileTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    #[DataProvider('dataGetProject')]
    public function testGetProject($data, $expected): void
    {
        $_POST = $data;

        $result = $this->routeApi->route(
            Request::create('/api/Profiles/GetProfileController/get', 'POST'),
            [
                'userid' => 1,
            ],
            true
        );

        $this->assertEquals($expected, $result);
    }

    public static function dataGetProject()
    {
        return [
            [
                [
                    'hostId' => 1,
                    'profile' => 'testProfile',
                ],
                [
                    'description' => 'testDescription',
                    'config' => [],
                    'name' => 'testProfile',
                    'used_by' => [],
                    'devices' => [],
                    'project' => 'default',
                ],
            ],
        ];
    }
}
