<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetProfileTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    /**
     * @dataProvider dataGetProject
     */
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

    public function dataGetProject()
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
