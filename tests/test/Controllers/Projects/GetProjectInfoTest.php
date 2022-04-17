<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

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
        $request =  new Request();
        $request = $request->create(
            "api/Projects/GetProjectInfoController/get",
            "POST",
            $data,
            [],
            [],
            ["HTTP_USERID"=>1],
        );
        $context = new RequestContext();
        $context->fromRequest($request);
        $result = $this->routeApi->route(
            $request,
            $context,
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
