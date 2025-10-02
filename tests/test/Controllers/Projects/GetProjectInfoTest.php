<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class GetProjectInfoTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }
    /**
     * @dataProvider dataGetProject
     */
    public function test_get_project_info($data, $expected) :void
    {
        $_POST = $data;

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Projects/GetProjectInfoController/get')),
            ["userid"=>1],
            true
        );

        $this->assertEquals($expected, $result);
    }

    public function dataGetProject()
    {
        return [
            [
                [
                    "hostId"=>1,
                    "project"=>"testProject"
                ],
                [
                    "description" => "testProjectDescription",
                    "config" =>  [
                        "features.images" => "true",
                        "features.profiles" => "true",
                        "features.storage.volumes" => "true",
                        "features.storage.buckets" => "true",
                    ],
                    "name" => "testProject",
                    "used_by" => [
                        //NOTE formating has to be exact or PHPUnit is going to
                        //     complain
                        0 => '<span href=\'/profiles/1/default?project=testProject\' class=\'\' data-navigo>
                <i class=\'fas fa-users pe-1\'></i>
                default
                <i class=\'fas fa-project-diagram ps-2 pe-1\'></i> testProject
            </span>',

                    ],
                    "users"=>[
                        "testUser"
                    ]
                ]
            ]
        ];
    }
}
