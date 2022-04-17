<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class GetProfileTest extends TestCase
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
    public function testGetProject($data, $expected) :void
    {
        $request =  new Request();
        $request = $request->create(
            "api/Profiles/GetProfileController/get",
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
                    "profile"=>"testProfile"
                ],
                [
                    "description" => "testDescription",
                    "config" =>  [],
                    "name" => "testProfile",
                    "used_by" => [],
                    "devices" => [],
                ]
            ]
        ];
    }
}
