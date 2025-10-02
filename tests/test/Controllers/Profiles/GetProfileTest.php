<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

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
        $_POST = $data;

        $result = $this->routeApi->route(
            array_filter(explode('/', '/Profiles/GetProfileController/get')),
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
                    "profile"=>"testProfile"
                ],
                [
                    "description" => "testDescription",
                    "config" =>  [],
                    "name" => "testProfile",
                    "used_by" => [],
                    "devices" => [],
                    "project" => "default"
                ]
            ]
        ];
    }
}
