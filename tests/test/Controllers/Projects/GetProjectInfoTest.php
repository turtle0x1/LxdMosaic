<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\Attributes\DataProvider;

final class GetProjectInfoTest extends TestCase
{
    private $routeApi;
    #[\Override]
    protected function setUp(): void
    {
        $container = (new \DI\ContainerBuilder)->useAttributes(true)->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }

    #[DataProvider('dataGetProject')]
    public function testGetProjectInfo($data, $expected): void
    {
        $_POST = $data;

        $result = $this->routeApi->route(
            Request::create('/api/Projects/GetProjectInfoController/get', 'POST'),
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
                    'project' => 'testProject',
                ],
                [
                    'description' => 'testProjectDescription',
                    'config' => [
                        'features.images' => 'true',
                        'features.profiles' => 'true',
                        'features.storage.volumes' => 'true',
                        'features.storage.buckets' => 'true',
                    ],
                    'name' => 'testProject',
                    'used_by' => [
                        //NOTE formating has to be exact or PHPUnit is going to
                        //     complain
                        0 => '<span href=\'/profiles/1/default?project=testProject\' class=\'\' data-navigo>
                <i class=\'fas fa-users pe-1\'></i>
                default
                <i class=\'fas fa-project-diagram ps-2 pe-1\'></i> testProject
            </span>',

                    ],
                    'users' => ['testUser'],
                ],
            ],
        ];
    }
}
