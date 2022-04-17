<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class CreateProjectTest extends TestCase
{
    public function setUp() :void
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAnnotations(true);
        $container = $builder->build();
        $this->routeApi = $container->make("dhope0000\LXDClient\App\RouteApi");
    }
    /**
     * @dataProvider data_createProjectData
     */
    public function test_createProject($data, $expected) :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/Projects/CreateProjectController/create",
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

    public function data_createProjectData()
    {
        return [
            [
                [
                    "hosts"=>[1],
                    "name"=>"testProject",
                    "description"=>"testProjectDescription"
                ],
                [
                    "state"=>"success", "message"=>"Created Projects"
                ]
            ]
        ];
    }
}
